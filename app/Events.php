<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Events
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereDateNotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereTypeEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events whereUserId($value)
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events where($column, $operator, $value)
 * @mixin \Eloquent
 */
class Events extends Model
{
    protected $table = 'events';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
