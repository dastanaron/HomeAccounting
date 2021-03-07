<?php
declare(strict_types=1);

namespace App\Components;

use App\{
    Models
};

class Currency
{
    public static function convertCurrency($value, int $currencyCode): float
    {
        $currency = Models\Currency::whereNumCode($currencyCode)->first();

        if (!$currency) {
            throw new \Exception('Error currency code');
        }

        $sum = $currency->value / $currency->nominal * $value;

        return round($sum, 2);
    }
}
