<?php

namespace App\Http\Controllers\InternalApi;

use App\Models;
use Auth;
use Illuminate\Http;
use App\Http\Controllers;
use App\Integrations\nalogRu;
use Illuminate\Support\Facades\Response;

class NalogRuSettingController extends Controllers\Controller
{
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
            return $this->prepareResponse(['integration' => null, 'message' => 'integration not found']);
        }
        $data = [];
        $data['integration'] = $integration;
        $data['meta']        = $this->integrationFacade->getMetaFromIntegration($integration);
        return $this->prepareResponse($data);
    }

    /**
     * @param Http\Request $request
     * @return Http\JsonResponse
     */
    public function createIntegration(Http\Request $request) {
        $userId = Auth::id();
        $meta = $this->integrationFacade->createMetaFromRequest($request);
        $isCreated = $this->integrationFacade->getCRUD()->create($userId, $meta);
        return $this->prepareResponse(['created' => $isCreated]);
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
