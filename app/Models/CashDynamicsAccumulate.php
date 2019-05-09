<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $user_id
 * @property string $month
 * @property float $sum
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|CashDynamicsAccumulate whereCreatedAt($value)
 * @method static Eloquent\Builder|CashDynamicsAccumulate whereId($value)
 * @method static Eloquent\Builder|CashDynamicsAccumulate whereMonth($value)
 * @method static Eloquent\Builder|CashDynamicsAccumulate whereSum($value)
 * @method static Eloquent\Builder|CashDynamicsAccumulate whereUpdatedAt($value)
 * @method static Eloquent\Builder|CashDynamicsAccumulate whereUserId($value)
 * @mixin \Eloquent
 */
class CashDynamicsAccumulate extends Eloquent\Model
{
    protected $table = 'cash_dynamics_accumulate';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
