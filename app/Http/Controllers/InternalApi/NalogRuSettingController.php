<?php

namespace App\Http\Controllers\InternalApi;

use App\Library\Exceptions;
use App\Models;
use Auth;
use Illuminate\Http;
use App\Http\Controllers;
use App\Integrations\nalogRu;
use Illuminate\Support\Facades\Response;

class NalogRuSettingController extends Controllers\Controller
{
    const ANSWER_CODE_SUCCESS               = 1;
    const ANSWER_CODE_CLIENTS_IS_EXISTS     = 2;
    const ANSWER_CODE_PHONE_IS_INVALID      = 3;
    const ANSWER_CODE_USER_NOT_FOUND        = 4;
    const ANSWER_CODE_INTEGRATION_NOT_FOUND = 5;
    const ANSWER_CODE_INTEGRATION_IS_EXISTS = 6;
    const ANSWER_CODE_AUTHORIZATION_FAILED  = 7;
    const ANSWER_CODE_BAD_REQUEST       = 400;
    const ANSWER_CODE_UNDEFINED_ERROR   = 500;

    /**
     * @var nalogRu\Facade
     */
    private $integrationFacade;

    /**
     * NalogRuSettingController constructor.
     */
    public function __construct()
    {
        $this->integrationFacade = nalogRu\Facade::getInstance();
    }

    /**
     * @return Http\JsonResponse
     */
    public function getSettings() {
        $userId = Auth::id();
        $integration = $this->integrationFacade->getCRUD()->getIntegrationByUserId($userId);
        if ($integration === null) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_INTEGRATION_NOT_FOUND, 'integration' => null, 'message' => 'integration not found']);
        }
        $data = [];
        $data['integration'] = $integration;
        $data['meta']        = $this->integrationFacade->getMetaFromIntegration($integration);
        $data['code']        = self::ANSWER_CODE_SUCCESS;
        return $this->prepareResponse($data);
    }

    /**
     * @param Http\Request $request
     * @return Http\JsonResponse
     */
    public function update(Http\Request $request) {
        $userId = Auth::id();
        $meta        = $this->integrationFacade->createMetaFromRequest($request);

        $answerAuthorization = $this->integrationFacade->getApi()->login($meta->phone, $meta->smsCode);

        $isActiveIntegration = (bool) $request->input('isActive');

        if ($answerAuthorization->code() !== 200) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_AUTHORIZATION_FAILED , 'message' => 'authorization failed']);
        }

        $isUpdated   = $this->integrationFacade->getCRUD()->updateIntegration($userId, $meta, $isActiveIntegration);

        if ($isUpdated) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_SUCCESS , 'message' => 'integration was updated']);
        }

        return $this->prepareResponse(['code' => self::ANSWER_CODE_BAD_REQUEST , 'message' => 'integration cannot be updated']);
    }

    /**
     * @param Http\Request $request
     * @return Http\JsonResponse
     */
    public function register(Http\Request $request)
    {
        $userId = Auth::id();
        try {
            $meta = $this->integrationFacade->createMetaFromRequest($request);
        }
        catch (Exceptions\BadRequestException $e) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_BAD_REQUEST , 'message' => $e->getMessage()], 400);
        }

        $integration = $this->integrationFacade->getCRUD()->getIntegrationByUserId($userId);

        if ($integration !== null) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_INTEGRATION_IS_EXISTS , 'message' => 'integration is already exists']);
        }

        $registerAnswer = $this->integrationFacade->getApi()->register($meta->email, $meta->name, $meta->phone);

        if ($registerAnswer->code() === 204) {
            $this->integrationFacade->getCRUD()->create($userId, $meta);
            return $this->prepareResponse(['code' => self::ANSWER_CODE_SUCCESS, 'registered' => true, 'message' => 'sms message was sent']);
        }
        if ($registerAnswer->code() === 409) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_CLIENTS_IS_EXISTS, 'registered' => false, 'message' => 'client is exists']);
        }

    }

    /**
     * @param Http\Request $request
     * @return Http\JsonResponse
     */
    public function restorePassword(Http\Request $request)
    {
        $meta = $this->integrationFacade->createMetaFromRequest($request);
        $restorePasswordAnswer = $this->integrationFacade->getApi()->restorePassword($meta->phone);
        if ($restorePasswordAnswer->code() === 204) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_SUCCESS, 'message' => 'sms message was sent']);
        }
        if ($restorePasswordAnswer->code() === 404) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_USER_NOT_FOUND, 'message' => 'client is exists']);
        }
    }

    /**
     * @param Http\Request $request
     * @return Http\JsonResponse
     */
    public function createIntegration(Http\Request $request) {
        $userId = Auth::id();
        $meta = $this->integrationFacade->createMetaFromRequest($request);

        if ($meta->smsCode === null) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_BAD_REQUEST , 'message' => 'smsCode is required'], 400);
        }

        $answerAuthorization = $this->integrationFacade->getApi()->login($meta->phone, $meta->smsCode);

        if ($answerAuthorization->code() !== 200) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_AUTHORIZATION_FAILED , 'message' => 'authorization failed']);
        }

        $isCreated = $this->integrationFacade->getCRUD()->create($userId, $meta);
        if ($isCreated) {
            return $this->prepareResponse(['code' => self::ANSWER_CODE_SUCCESS, 'message' => 'integration is successfully created']);
        }
        return $this->prepareResponse(['code' => self::ANSWER_CODE_UNDEFINED_ERROR, 'message' => 'undefined error, cannot record integration in storage']);
    }

    /**
     * @param array $data
     * @param int $code
     * @return Http\JsonResponse
     */
    private function prepareResponse(array $data, int $code = 200): Http\JsonResponse
    {
        return Response::json($data)->setStatusCode($code);
    }
}
