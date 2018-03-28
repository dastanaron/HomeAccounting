<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class Funds extends Model
{
    protected $table = 'funds';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function bills()
    {
        return $this->hasOne('App\Bills', 'id', 'bills_id');
    }
}
