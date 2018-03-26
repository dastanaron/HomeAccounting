<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bills
 * @package App
 *
 * @property integer $id;
 * @property integer $user_id;
 * @property string $name;
 * @property integer $sum;
 * @property \DateTime $deadline;
 * @property string $comment;
 * @property \DateTime $created_at;
 * @property \DateTime $updated_at;
 *
 * Relation property
 * @property User $user;
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
