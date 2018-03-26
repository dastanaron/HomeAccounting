<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**+
 * Class revCategories
 * @package App
 *
 * @property integer $id;
 * @property integer $user_id;
 * @property string $name;
 * @property \DateTime $created_at;
 * @property \DateTime $updated_at;
 *
 * Relation property
 * @property User $user;
 */
class revCategories extends Model
{
    protected $table = 'rev_categories';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
