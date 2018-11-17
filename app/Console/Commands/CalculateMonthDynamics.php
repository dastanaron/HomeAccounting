<?php

namespace App\Console\Commands;

use App\CashDynamicsAccumulate;
use App\Funds;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Тестовый функционал сбора данных.
 * Нужно будет переделать, но пока пусть так поработает.
 * Подход не очень хороший, но все-таки рабочий
 *
 * Class CalculateMonthDynamics
 * @package App\Console\Commands
 */
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

    /**
     * Handle
     */
    public function handle()
    {

        foreach($this->getUsersIds() as $userId) {
            $this->userId = $userId;
            $this->processEnvelope();
        }

    }

    /**
     * @throws \Exception
     */
    private function processEnvelope()
    {
        $firstDate = new Carbon($this->getFirstDate());
        $lastDate = new Carbon($this->getLatestDate());

        $monthDiff = $firstDate->diff($lastDate)->m;

        $startDate = new Carbon($firstDate->format('Y-m-01'));

        $array = [];

        for($start = 0; $start<$monthDiff; $start++) {
            $month = $startDate->addMonth(1);

            $array[$month->format('Y-m')] = $this->getDiffByMonth($month);
        }

        //clear table by user_id
        CashDynamicsAccumulate::where('user_id', '=', $this->userId)->delete();


        foreach($array as $month => $value) {
            $newCashDinamicElement = new CashDynamicsAccumulate();
            $newCashDinamicElement->user_id = $this->userId;
            $newCashDinamicElement->month = $month;
            $newCashDinamicElement->sum = $value;
            $newCashDinamicElement->save();
        }
    }

    /**
     * @return array
     */
    private function getUsersIds()
    {
        $users = User::select('id')->get();

        $array = [];

        foreach ($users as $user) {
            $array[] = $user->id;
        }

        return $array;
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

        return !is_null($result) ? $result->date : null;
    }

    /**
     * Определяем дату последней записи
     * @return mixed|string
     */
    private function getLatestDate()
    {
        $query = Funds::whereUserId($this->userId)->orderBy('date', 'desc');

        $result = $query->first();

        return !is_null($result) ? $result->date : null;
    }
}
