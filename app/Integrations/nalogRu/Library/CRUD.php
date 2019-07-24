<?php


namespace App\Integrations\nalogRu\Library;

use App\Models;
use App\Library\Utilities;
use App\Integrations\nalogRu\Objects;

class CRUD
{
    const INTEGRATION_NAME = 'nalog_ru';

    /**
     * @param int $userId
     * @param Objects\Meta $meta
     * @return bool
     */
    public function create(int $userId, Objects\Meta $meta): bool
    {
        $model = new Models\Integration();
        $model->name      = self::INTEGRATION_NAME;
        $model->user_id   = $userId;
        $model->is_active = 1;
        $model->meta      = Utilities\Json::encode($meta);
        return $model->save();
    }

    /**
     * @param int $userId
     * @return Models\Integration|null
     */
    public function getIntegrationByUserId(int $userId): ?Models\Integration
    {
        return Models\Integration::where(['user_id' => $userId, 'name' => self::INTEGRATION_NAME])->first();
    }

    /**
     * @param int $userId
     * @return Objects\Meta
     */
    public function getMetaByUserId(int $userId): Objects\Meta
    {
        $integration = $this->getIntegrationByUserId($userId);
        $decodedMeta = Utilities\Json::decode($integration->meta);
        $meta = new Objects\Meta();

        //todo: сделать валидацию мета данных
        $meta->name    = $decodedMeta['name'];
        $meta->email   = $decodedMeta['email'];
        $meta->smsCode = $decodedMeta['smsCode'];
        $meta->phone   = $decodedMeta['phone'];
        return $meta;
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    protected function _validatePhone($phoneNumber): bool
    {
        return preg_match('#^\+7[0-9]{10}$#', $phoneNumber);
    }
}