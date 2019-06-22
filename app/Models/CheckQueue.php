<?php

namespace App\Models;

use Illuminate\Database\Eloquent;
use Illuminate\Support;

/**
 * @property string $uuid
 * @property int $user_id
 * @property string $qrcode
 * @property string $control_sum
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|Charts whereUuid($value)
 * @method static Eloquent\Builder|Charts whereUserId($value)
 * @mixin \Eloquent
 */
class CheckQueue extends Eloquent\Model
{
    protected $table = 'check_queue';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        self::updating(function(CheckQueue $model){
            $model->setControlSum();
        });

        self::saving(function (CheckQueue $model) {
            if (empty($model->uuid)) {
                $model->uuid = Support\Str::uuid()->toString();
            }
            $model->setControlSum();
        });
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function setControlSum()
    {
        $this->control_sum = md5($this->qrcode);
    }
}
