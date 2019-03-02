<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bills whereCurrency($value)
 */
class Bills extends Model
{
    protected $table = 'bills';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
