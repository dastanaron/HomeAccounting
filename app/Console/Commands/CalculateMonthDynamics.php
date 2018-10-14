<?php

namespace App\Console\Commands;

use App\Funds;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;

class CalculateMonthDynamics extends Command
{

    private $userId = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:monthDynamics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рассчет ежемесячной динамики накоплений';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $this->userId = 1;

        $firstDate = new Carbon($this->getFirstDate());
        $lastDate = new Carbon($this->getLatestDate());

        $monthDiff = $firstDate->diff($lastDate)->m;

        $startDate = new Carbon($firstDate->format('Y-m-01'));

        $array = [];

        for($start = 0; $start<$monthDiff; $start++)
        {
            $month = $startDate->addMonth(1);

            $array[$month->format('Y-m')] = $this->getDiffByMonth($month);
        }

        dump($lastDate, $array);



    }

    /**
     * Отдает разницу между суммой дохода и расхода за месяц
     * @param Carbon $date
     * @return int
     * @throws \Exception
     */
    private function getDiffByMonth(Carbon $date)
    {
        $firstDateInMonth = $date->format('Y-m-01');

        $endDateInMonth = $date->format('Y-m-'.$date->daysInMonth);

        $funds = Funds::whereBetween('date', [$firstDateInMonth, $endDateInMonth]);


        $incomeSum = 0;

        $expenseSum = 0;

        foreach($funds->get() as $item)
        {
            if($item->rev == 1)
            {
                $incomeSum += $item->sum;
            }
            elseif($item->rev == 2)
            {
                $expenseSum += $item->sum;
            }
            else
            {
                throw new \Exception('Ошибка, не расход и не доход' . var_export($item->toArray(), true));
            }

        }

        return $incomeSum - $expenseSum;

    }


    /**
     * Определяем дату первой записи
     * @return mixed|string
     */
    private function getFirstDate()
    {
        $query = Funds::whereUserId($this->userId)->orderBy('date', 'asc');

        $result = $query->first();

        return $result->date;
    }

    /**
     * Определяем дату последней записи
     * @return mixed|string
     */
    private function getLatestDate()
    {
        $query = Funds::whereUserId($this->userId)->orderBy('date', 'desc');

        $result = $query->first();

        return $result->date;
    }
}
