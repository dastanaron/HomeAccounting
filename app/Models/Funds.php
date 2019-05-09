<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
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
 * @property-read Bills $bills
 * @method static Eloquent\Builder|Funds whereBillsId($value)
 * @method static Eloquent\Builder|Funds whereUserId($value)
 * @method static Eloquent\Builder|Funds whereCategoryId($value)
 * @method static Eloquent\Builder|Funds whereCause($value)
 * @method static Eloquent\Builder|Funds whereCreatedAt($value)
 * @method static Eloquent\Builder|Funds whereDate($value)
 * @method static Eloquent\Builder|Funds whereId($value)
 * @method static Eloquent\Builder|Funds whereRev($value)
 * @method static Eloquent\Builder|Funds whereSum($value)
 * @method static Eloquent\Builder|Funds whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Funds extends Eloquent\Model
{
    protected $table = 'funds';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function bills()
    {
        return $this->hasOne('App\Models\Bills', 'id', 'bills_id');
    }
}
