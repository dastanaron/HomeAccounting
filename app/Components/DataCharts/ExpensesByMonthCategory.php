<?php

namespace App\Components\DataCharts;

use App\Funds;

class ExpensesByMonthCategory extends AbstractChartData
{

    public function getData()
    {
        // TODO: Implement getData() method.
    }

    public function getJsonByChart()
    {
        // TODO: Implement getJsonByChart() method.
    }


    /**
     * @return Funds|\Illuminate\Database\Query\Builder
     */
    public function queryFunds()
    {
        return Funds::select('funds.user_id', 'funds.sum', 'funds.date', 'funds.cause', 'rev_categories.id as category_id', 'rev_categories.name as category_name')
            ->leftJoin('rev_categories', 'funds.category_id', 'rev_categories.id')
            ->where('funds.user_id', '=', $this->userId)
            ->where('funds.rev', '=', $this->fundsRev)
            ->whereBetween('funds.date', [$this->dateStart, $this->dateEnd]);
    }

}