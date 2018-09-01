<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\revCategories
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\revCategories whereUserId($value)
 * @mixin \Eloquent
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
