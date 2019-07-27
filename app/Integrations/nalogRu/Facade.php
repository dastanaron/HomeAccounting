<?php

namespace App\Integrations\nalogRu;

use App\Integrations\nalogRu\Library\Exception;
use App\Models;
use App\Library\Singleton;
use App\Library\Utilities;
use App\Library\Exceptions;
use Illuminate\Http;

/**
 * Class Facade
 * @package App\Integrations\nalogRu
 * @method static self getInstance()
 */
class Facade extends Singleton
{
    /**
     * @var Library\API
     */
    private $api;

    /**
     * @var Library\CRUD
     */
    private $crud;

    /**
     * Facade constructor.
     */
    protected function __construct()
    {
        $this->api = new Library\API();
        $this->crud = new Library\CRUD();
        parent::__construct();
    }

    /**
     * @return Library\API
     */
    public function getApi(): Library\API
    {
        return $this->api;
    }

    /**
     * @return Library\CRUD
     */
    public function getCRUD(): Library\CRUD
    {
        return $this->crud;
    }

    /**
     * @param Models\Integration $integration
     * @return Objects\Meta
     */
    public function getMetaFromIntegration(Models\Integration $integration): Objects\Meta
    {
        return Objects\Meta::parseFromArray(Utilities\Json::decode($integration->meta));
    }

    /**
     * @param array $array
     * @return bool
     */
    public function validateMetaFromArray(array $array): bool
    {
        return Objects\Meta::validateMetaArray($array);
    }

    /**
     * @param Http\Request $request
     * @return Objects\Meta
     * @throws Exceptions\BadRequestException
     */
    public function createMetaFromRequest(Http\Request $request): Objects\Meta
    {
        $data = [
            Objects\Meta::ARRAY_KEY_NAME     => $request->input(Objects\Meta::ARRAY_KEY_NAME),
            Objects\Meta::ARRAY_KEY_EMAIL    => $request->input(Objects\Meta::ARRAY_KEY_EMAIL),
            Objects\Meta::ARRAY_KEY_PHONE    => $request->input(Objects\Meta::ARRAY_KEY_PHONE),
            Objects\Meta::ARRAY_KEY_SMS_CODE => $request->input(Objects\Meta::ARRAY_KEY_SMS_CODE),
        ];
        try {
            $meta = Objects\Meta::parseFromArray($data);
        }
        catch (Exception $e) {
            throw new Exceptions\BadRequestException($request);
        }
        return $meta;
    }

}