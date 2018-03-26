<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Funds
 * @package App
 *
 * @property integer $id;
 * @property integer $bills_id;
 * @property integer $rev;
 * @property integer $category_id;
 * @property integer $sum;
 * @property string $cause;
 * @property \DateTime $date;
 * @property \DateTime $created_at;
 * @property \DateTime $updated_at;
 *
 * Relation property
 *
 * @property Bills $bills;
 *
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
