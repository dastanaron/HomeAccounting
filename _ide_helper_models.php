<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
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
 * @property int $id
 * @property int $user_id
 * @property string $social_network
 * @property string $social_id
 * @property string|null $comment
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $photo
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereSocialNetwork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SocialNetwork whereUserId($value)
 */
	class SocialNetwork extends \Eloquent {}
}

namespace App{
/**
 * App\Charts
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $control_sum
 * @property mixed $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereControlSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereUserId($value)
 * @mixin \Eloquent
 */
	class Charts extends \Eloquent {}
}

namespace App{
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Events where($column, $operator, $value)
 * @mixin \Eloquent
 */
	class Events extends \Eloquent {}
}

namespace App{
/**
 * App\Funds
 *
 * @property int $id
 * @property int $bills_id
 * @property int $user_id
 * @property int $rev
 * @property int $category_id
 * @property int $sum
 * @property string $cause
 * @property string $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Bills $bills
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereBillsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereRev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Funds whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Funds extends \Eloquent {}
}

namespace App{
/**
 * App\revCategories
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereUserId($value)
 * @mixin \Eloquent
 */
	class revCategories extends \Eloquent {}
}

namespace App{
/**
 * Class Bills
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $sum
 * @property string|null $deadline
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills where($column, $operator, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereUserId($value)
 * @mixin \Eloquent
 */
	class Bills extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * 
 * Relation property
 * @property \App\SocialNetwork $social_network;
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SocialNetwork[] $social_network
 */
	class User extends \Eloquent {}
}

