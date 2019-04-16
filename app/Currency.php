<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Currency
 *
 * @property string $external_id
 * @property int $num_code
 * @property string $char_code
 * @property int|null $nominal
 * @property string|null $name
 * @property float $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereCharCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereNumCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereValue($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{
    protected $table = 'currencies';
    public $timestamps = false;
}
