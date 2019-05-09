<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * Class SocialNetwork
 *
 * @package App
 * @property integer $id;
 * @property integer $user_id;
 * @property string $social_network;
 * @property string $social_id;
 * @property string $comment;
 * @property string $first_name;
 * @property string $last_name;
 * @property string $photo;
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|SocialNetwork whereComment($value)
 * @method static Eloquent\Builder|SocialNetwork whereCreatedAt($value)
 * @method static Eloquent\Builder|SocialNetwork whereFirstName($value)
 * @method static Eloquent\Builder|SocialNetwork whereId($value)
 * @method static Eloquent\Builder|SocialNetwork whereLastName($value)
 * @method static Eloquent\Builder|SocialNetwork wherePhoto($value)
 * @method static Eloquent\Builder|SocialNetwork whereSocialId($value)
 * @method static Eloquent\Builder|SocialNetwork whereSocialNetwork($value)
 * @method static Eloquent\Builder|SocialNetwork whereUpdatedAt($value)
 * @method static Eloquent\Builder|SocialNetwork whereUserId($value)
 * @mixin \Eloquent
 */
class SocialNetwork extends Eloquent\Model
{
    protected $table = 'social_networks';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public static function getOrCreateObject($social_id = null)
    {
        if(!empty($social_id)) {
            $socialNetwork = self::where('social_id', '=', $social_id)->first();
        }

        if(empty($socialNetwork)) {
            $socialNetwork = new self();
        }

        return $socialNetwork;

    }
}
