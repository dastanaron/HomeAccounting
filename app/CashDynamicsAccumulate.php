<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * \App\CashDynamicsAccumulate
 *
 * @property int $id
 * @property int $user_id
 * @property string $month
 * @property float $sum
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CashDynamicsAccumulate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CashDynamicsAccumulate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CashDynamicsAccumulate whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CashDynamicsAccumulate whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CashDynamicsAccumulate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CashDynamicsAccumulate whereUserId($value)
 * @mixin \Eloquent
 */
class CashDynamicsAccumulate extends Model
{
    protected $table = 'cash_dynamics_accumulate';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
