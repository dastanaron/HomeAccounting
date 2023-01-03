<?php

namespace App\Models;

use Illuminate;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $api_token
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * Relation property
 * @property SocialNetwork $social_network;
 * @property-read Illuminate\Notifications\DatabaseNotificationCollection|Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static Illuminate\Database\Eloquent\Builder|User whereApiToken($value)
 * @method static Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Illuminate\Foundation\Auth\User
{
    use Illuminate\Notifications\Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    public function social_network()
    {
        return $this->hasMany('App\SocialNetwork', 'user_id', 'id');
    }
}
