<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @method static Eloquent\Builder|revCategories whereCreatedAt($value)
 * @method static Eloquent\Builder|revCategories whereId($value)
 * @method static Eloquent\Builder|revCategories whereName($value)
 * @method static Eloquent\Builder|revCategories whereUpdatedAt($value)
 * @method static Eloquent\Builder|revCategories whereUserId($value)
 * @mixin \Eloquent
 */
class revCategories extends Eloquent\Model
{
    protected $table = 'rev_categories';

    protected $dateFormat = 'Y-m-d H:i:s';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
