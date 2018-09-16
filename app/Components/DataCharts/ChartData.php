<?php
/**
 * Created by PhpStorm.
 * User: dastanaron
 * Date: 16.09.18
 * Time: 16:14
 */

namespace App\Components\DataCharts;


interface ChartData
{
    /**
     * @param $userId
     * @param $dateStart
     * @param $dateEnd
     * @param $fundsRev
     * @return mixed
     * Alternative constructor
     */
    public static function init($userId, $dateStart, $dateEnd, $fundsRev);

    public function getData();

    public function getJson();

    public function getJsonByChart();

}