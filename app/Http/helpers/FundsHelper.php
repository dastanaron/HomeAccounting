<?php

namespace App\Http\helpers;


use App\Bills;
use App\Funds;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Http\Request;
use Auth;

class FundsHelper
{

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var array|null|string
     */
    protected $bills_id;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user_id = Auth::user()->id;
        $this->bills_id = $request->input('bills_id');
    }

    public function getFunds()
    {

        $paginate = $this->request->input('paginate');

        if(empty($paginate)) {
            $paginate = 20;
        }

        $query = $this->SelectFilter()->orderBy('funds.date', 'desc');

        $totalSum = $this->totalSum($query);

        return ['paginate' =>$query->paginate($paginate), 'totalSum' => $totalSum];
    }

    protected function totalSum($query)
    {
        $result = $query->get();

        $sum = 0;

        foreach ($result as $element) {
            $sum += $element->sum;
        }

        return $sum;

    }

    protected function SelectFilter()
    {
        $rev = $this->request->input('rev');
        $bills_id = $this->request->input('bills_id');
        $category_id = $this->request->input('category_id');
        $date = ['start' => $this->request->input('date_start'), 'end' => $this->request->input('date_end')];
        $sum = $this->request->input('sum');

        $query = $this->FundsSelectQuery();

        if(!empty($rev)) {
            $query->where('funds.rev', '=', $rev);
        }

        if(!empty($bills_id)) {
            $query->where('funds.bills_id', '=', $bills_id);
        }

        if(!empty($category_id)) {
            $query->where('funds.category_id', '=', $category_id);
        }

        if(!empty($date)) {

            if(!empty($date['start'])) {
                $dateStart = new \DateTime($date['start']);
            }
            else {
                $dateStart = null;
            }

            if(!empty($date['end'])) {
                $dateEnd= new \DateTime($date['end']);
            }
            else {
                $dateEnd = new \DateTime();
            }

            $dayPervious = !empty($dateStart) ? $dateStart->format('Y-m-d 00:00:00') : 0;
            $dayNext = $dateEnd->format('Y-m-d 23:59:59');

            $query->whereBetween('funds.date', [$dayPervious, $dayNext]);
        }

        if(!empty($sum)) {
            $query->where('funds.sum', '=', $sum);
        }

        return $query;

    }

    protected function FundsSelectQuery()
    {
        return Funds::select('funds.*', 'bills.name as bills_name', 'rev_categories.name as category_name')
            ->leftJoin('bills', 'funds.bills_id', '=', 'bills.id')
            ->leftJoin('rev_categories', 'funds.category_id', 'rev_categories.id')
            ->where('funds.user_id', '=', $this->user_id);
    }

    public function createFunds()
    {
        $funds = new Funds();

        $funds->user_id = $this->user_id;
        $funds->bills_id = $this->bills_id;
        $funds->rev = $this->request->input('rev');
        $funds->category_id = $this->request->input('category_id');
        $funds->sum = $this->request->input('sum');
        $funds->cause = $this->request->input('cause');
        $funds->date = $this->request->input('date');

        $saved = $funds->save();

        if($saved) {
            $this->billsCalculate($funds->rev, $funds->sum);
        }

        return $saved;

    }

    public function setFunds()
    {
        $funds = Funds::whereId($this->request->input('funds_id'))->first();

        if(empty($funds)) {
            return false;
        }

        /**
         * Послучаем сторону изменения (доход или расход), в зависимости от этого, отнимаем старую сумму или
         * прибавляем ее. Далее записываем новые данные в модель, а потом считаем заново, уже с новой суммой.
         */
        $rev = $this->request->input('rev');

        if($rev == 1) {
            $this->billsCalculate(2, $funds->sum);
        }
        else if($rev == 2) {
            $this->billsCalculate(1, $funds->sum);
        }

        $funds->rev = $rev;
        $funds->category_id = $this->request->input('category_id');
        $funds->sum = $this->request->input('sum');
        $funds->cause = $this->request->input('cause');
        $funds->date = $this->request->input('date');

        $saved = $funds->save();

        if($saved) {
            $this->billsCalculate($funds->rev, $funds->sum);
        }

        return $saved;
    }

    public function deleteFunds()
    {
        $funds = Funds::whereId($this->request->input('funds_id'))->first();

        if(empty($funds)) {
            return false;
        }

        $this->bills_id = $funds->bills_id;

        /**
         * Если удаляем, то берем значение, что это было расход или доход.
         * Сразу отнимаем или вычитаем из счета, а потом удаляем
         */
        $rev = $funds->rev;

        if($rev == 1) {
            $this->billsCalculate(2, $funds->sum);
        }
        else if($rev == 2) {
            $this->billsCalculate(1, $funds->sum);
        }

        return $funds->delete();

    }


    protected function billsCalculate($rev, $sum)
    {
        $bill = Bills::whereId($this->bills_id)->first();

        if($rev == 1) {
            $calcSum = $bill->sum + $sum;
            $bill->sum = $calcSum;
            $bill->save();
        }
        if($rev == 2) {
            $calcSum = $bill->sum - $sum;
            $bill->sum = $calcSum;
            $bill->save();
        }

    }
}