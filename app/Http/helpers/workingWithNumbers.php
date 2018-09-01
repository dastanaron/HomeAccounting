<?php
/**
 * Created by PhpStorm.
 * User: dastanaron
 * Date: 01.07.18
 * Time: 10:50
 */

namespace App\Http\helpers;


trait workingWithNumbers
{
    /**
     * @param $requestSum
     * @return float
     */
    protected function sumToFloat($requestSum)
    {
        return (float) str_replace(',', '.', $requestSum);
    }
}