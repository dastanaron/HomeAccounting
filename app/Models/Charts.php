<?php

namespace App\Models;

use Illuminate\Database\Eloquent;
use App\Library\Utilities;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $control_sum
 * @property mixed $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|Charts whereControlSum($value)
 * @method static Eloquent\Builder|Charts whereCreatedAt($value)
 * @method static Eloquent\Builder|Charts whereData($value)
 * @method static Eloquent\Builder|Charts whereId($value)
 * @method static Eloquent\Builder|Charts whereType($value)
 * @method static Eloquent\Builder|Charts whereUpdatedAt($value)
 * @method static Eloquent\Builder|Charts whereUserId($value)
 * @mixin \Eloquent
 */
class Charts extends Eloquent\Model
{
    protected $table = 'charts';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getData()
    {;
        return Utilities\Json::decode(Utilities\Base64::decode($this->data));
    }

    public function setData($data)
    {
        $this->data = Utilities\Base64::encode(Utilities\Json::encode($data));
    }
}
