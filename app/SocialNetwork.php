<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialNetwork
 * @package App
 *
 * @property integer $id;
 * @property integer $user_id;
 * @property string $social_network;
 * @property string $social_id;
 * @property string $comment;
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class SocialNetwork extends Model
{
    protected $table = 'social_networks';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
