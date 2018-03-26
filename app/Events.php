<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Events
 * @package App
 *
 * @property integer $id;
 * @property integer $user_id;
 * @property integer $type_event;
 * @property string $head;
 * @property string $message;
 * @property boolean $completed;
 * @property \DateTime $date_notif;
 * @property \DateTime $created_at;
 * @property \DateTime $updated_at;
 *
 * Relation property
 * @property User $user;
 *
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
