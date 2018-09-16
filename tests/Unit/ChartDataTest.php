<?php

namespace Tests\Unit;

use App\Components\DataCharts\ExpensesByMonthCategory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChartDataTest extends TestCase
{
    /**
     * @return void
     */
    public function testExpensesByMonthCategoryTest()
    {
        $expensesClass = ExpensesByMonthCategory::init(1, '2018-07-01 00:00:00', '2018-07-31 23:59:59', 2);

        //dump($expensesClass->getData());

        $this->assertTrue(is_array($expensesClass->getData()) && !empty($expensesClass->getData()));
    }
}