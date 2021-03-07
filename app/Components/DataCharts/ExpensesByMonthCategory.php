<?php

namespace App\Components\DataCharts;

use App\{
    Components,
    Models
};
use App\Library\Utilities;

class ExpensesByMonthCategory extends AbstractChartData
{

    public function getData()
    {
        $dataGroupedCategory = $this->queryFunds()->get()->groupBy('category_id')->toArray();

        $data = array();

        foreach ($dataGroupedCategory as $categoryId => $dataByCategory)
        {

            $sum = 0;

            foreach ($dataByCategory as $item)
            {
                $categoryName = $item['category_name'];

                if($item['currency_code'] === 643) {
                    $sum += $item['sum'];
                } else {
                    $sum +=  Components\Currency::convertCurrency($item['sum'], $item['currency_code']);
                }
            }

            if(!empty($sum) && !empty($categoryName))
            {
                $data[$categoryId] = [
                    'name' => $categoryName,
                    'sum' => round($sum, 2),
                ];
            }

            unset($sum);
            unset($categoryName);

        }

        return $data;
    }

    /**
     * @return string
     * @throws Utilities\Exceptions\EncodingException
     */
    public function getJsonByChart()
    {
        $chartData = array();

        foreach($this->getData() as $categoryId => $data)
        {
            $chartData[] = [$data['name'], $data['sum']];
        }

        return Utilities\Json::encode($chartData);
    }


    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function queryFunds()
    {
        return Models\Funds::select('funds.user_id', 'funds.sum', 'funds.date', 'funds.cause', 'rev_categories.id as category_id', 'rev_categories.name as category_name', 'bills.currency as currency_code')
            ->leftJoin('rev_categories', 'funds.category_id', '=', 'rev_categories.id')
            ->leftJoin('bills', 'funds.bills_id', '=', 'bills.id')
            ->where('funds.user_id', '=', $this->userId)
            ->where('funds.rev', '=', $this->fundsRev)
            ->whereBetween('funds.date', [$this->dateStart, $this->dateEnd]);
    }

}
