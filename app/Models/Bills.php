<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * Class Bills
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $sum
 * @property int|null $currency
 * @property string|null $deadline
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|Bills where($column, $operator, $value)
 * @method static Eloquent\Builder|Bills whereComment($value)
 * @method static Eloquent\Builder|Bills whereCreatedAt($value)
 * @method static Eloquent\Builder|Bills whereDeadline($value)
 * @method static Eloquent\Builder|Bills whereId($value)
 * @method static Eloquent\Builder|Bills whereName($value)
 * @method static Eloquent\Builder|Bills whereSum($value)
 * @method static Eloquent\Builder|Bills whereUpdatedAt($value)
 * @method static Eloquent\Builder|Bills whereUserId($value)
 * @method static Eloquent\Builder|Bills whereCurrency($value)
 * @mixin \Eloquent
 */
class Bills extends Eloquent\Model
{
    protected $table = 'bills';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
