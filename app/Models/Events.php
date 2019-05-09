<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $type_event
 * @property string $head
 * @property string|null $message
 * @property int $completed
 * @property string $date_notif
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static Eloquent\Builder|Events whereCompleted($value)
 * @method static Eloquent\Builder|Events whereCreatedAt($value)
 * @method static Eloquent\Builder|Events whereDateNotif($value)
 * @method static Eloquent\Builder|Events whereHead($value)
 * @method static Eloquent\Builder|Events whereId($value)
 * @method static Eloquent\Builder|Events whereMessage($value)
 * @method static Eloquent\Builder|Events whereTypeEvent($value)
 * @method static Eloquent\Builder|Events whereUpdatedAt($value)
 * @method static Eloquent\Builder|Events whereUserId($value)
 * @method static Eloquent\Builder|Events where($column, $operator, $value)
 * @mixin \Eloquent
 */
class Events extends Eloquent\Model
{
    protected $table = 'events';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
