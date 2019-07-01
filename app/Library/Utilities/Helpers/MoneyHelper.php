<?php
declare(strict_types=1);

namespace App\Library\Utilities\Helpers;

class MoneyHelper
{
    /**
     * @param $sum
     * @return float
     */
    public function convertSumWithCommaToSumWithDot($sum) : float
    {
        return (float) str_replace(',', '.', $sum);
    }

    /**
     * @param $sum
     * @return string
     */
    public function convertSumWithDotToSumWithComma($sum) : string
    {
        return (string) str_replace('.', ',', $sum);
    }

    public function convertSumToInt($sum) : int
    {
        $prepared = preg_replace('#\.|\,#', '', $sum);
        return (int) $prepared;
    }
}