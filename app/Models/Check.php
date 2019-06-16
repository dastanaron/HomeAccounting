<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $user_id
 * @property string $json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|Charts whereId($value)
 * @method static Eloquent\Builder|Charts whereUserId($value)
 * @mixin \Eloquent
 */
class Check extends Eloquent\Model
{
    protected $table = 'check';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
