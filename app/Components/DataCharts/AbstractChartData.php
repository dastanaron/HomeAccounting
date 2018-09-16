<?php

namespace App\Components\DataCharts;


abstract class AbstractChartData implements ChartData
{

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $dateStart;

    /**
     * @var string
     */
    public $dateEnd;

    /**
     * @var int
     */
    public $fundsRev;

    /**
     * ExpensesByCategory constructor.
     * @param $userId
     * @param $dateStart
     * @param $dateEnd
     */
    public function __construct($userId, $dateStart, $dateEnd, $fundsRev=2)
    {
        $this->userId = $userId;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->fundsRev = $fundsRev;
    }

    /**
     * @param $userId
     * @param $dateStart
     * @param $dateEnd
     * @return ExpensesByCategory
     */
    public static function init($userId, $dateStart, $dateEnd, $fundsRev=2)
    {
        return new static($userId, $dateStart, $dateEnd, $fundsRev);
    }


    /**
     * @return array
     */
    abstract public function getData();

    /**
     * @return string
     */
    public function getJson()
    {
        return json_encode($this->getData());
    }


    /**
     * @return string
     */
    abstract public function getJsonByChart();

}