<?php

namespace App\Components\DataCharts;


use App\CashDynamicsAccumulate;

class DynamicAccumulate extends AbstractChartData
{

    public function getData()
    {
        $dataCollection = CashDynamicsAccumulate::select(['sum', 'month'])->where('user_id', '=', $this->userId)->get();

        return !is_null($dataCollection) ? $dataCollection->toArray() : [];
    }

    public function getJsonByChart()
    {
        $data = $this->getData();

        $xArray = [];

        $yArray = [];

        $stringX = '';
        $stringY = 'sum, ';

        foreach($data as $items) {
            $xArray['x'][] = $items['month'];
            $yArray['sum'][] = $items['sum'];
        }

        $stringX .= implode(', ', $xArray['x']);
        $stringY .= implode(', ', $yArray['sum']);

        $array = array();

        $x = explode(', ', $stringX);
        $y = explode(', ', $stringY);

        $array[] = $x;
        $array[] = $y;

        return json_encode($array);

    }

}