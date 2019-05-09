<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
 * @property string $external_id
 * @property int $num_code
 * @property string $char_code
 * @property int|null $nominal
 * @property string|null $name
 * @property float $value
 * @method static Eloquent\Builder|Currency whereCharCode($value)
 * @method static Eloquent\Builder|Currency whereExternalId($value)
 * @method static Eloquent\Builder|Currency whereName($value)
 * @method static Eloquent\Builder|Currency whereNominal($value)
 * @method static Eloquent\Builder|Currency whereNumCode($value)
 * @method static Eloquent\Builder|Currency whereValue($value)
 * @mixin \Eloquent
 */
class Currency extends Eloquent\Model
{
    protected $table = 'currencies';
    public $timestamps = false;
}
