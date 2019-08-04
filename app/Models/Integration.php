<?php

namespace App\Models;

use Illuminate\Database\Eloquent;
/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $is_active
 * @property string $meta
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|Funds whereUserId($value)
 * @method static Eloquent\Builder|Funds whereCreatedAt($value)
 * @method static Eloquent\Builder|Funds whereUpdatedAt($value)
 * @method static Eloquent\Builder|Funds whereIsActive($value)
 * @method static Eloquent\Builder|Funds whereName($value)
 * @mixin \Eloquent
 */
class Integration extends Eloquent\Model
{
    protected $table      = 'integrations';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
