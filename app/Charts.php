<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Charts
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string|null $control_sum
 * @property mixed $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereControlSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Charts whereUserId($value)
 * @mixin \Eloquent
 */
class Charts extends Model
{
    protected $table = 'charts';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getData()
    {
        return json_decode(base64_decode($this->data), true);
    }

    public function setData($data)
    {
        $this->data = base64_encode(json_encode($data));
    }
}
