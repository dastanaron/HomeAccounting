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
     * @param bool $active
     * @return bool
     */
    public function create(int $userId, Objects\Meta $meta, ?bool $active = true): bool
    {
        $model = new Models\Integration();
        $model->name      = self::INTEGRATION_NAME;
        $model->user_id   = $userId;
        $model->is_active = $active;
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
        $meta = Objects\Meta::parseFromArray($decodedMeta);
        return $meta;
    }

    /**
     * @param int $userId
     * @param Objects\Meta $meta
     * @return bool
     */
    public function updateMeta(int $userId, Objects\Meta $meta): bool
    {
        $model= $this->getIntegrationByUserId($userId);
        if ($model === null) {
            return false;
        }
        $model->meta = Utilities\Json::encode($meta);
        return $model->save();
    }

    /**
     * @param int $userId
     * @param Objects\Meta $meta
     * @param bool|null $active
     * @return bool
     */
    public function updateIntegration(int $userId, Objects\Meta $meta, ?bool $active = true): bool
    {
        $model= $this->getIntegrationByUserId($userId);
        if ($model === null) {
            return false;
        }
        $model->meta      = Utilities\Json::encode($meta);
        $model->is_active = $active;
        return $model->save();
    }

    /**
     * @param int $userId
     * @return bool|null
     */
    public function delete(int $userId): ?bool
    {
        return Models\Integration::where(['user_id' => $userId, 'name' => self::INTEGRATION_NAME])->delete();
    }
}