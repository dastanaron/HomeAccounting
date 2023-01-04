<?php

namespace App\Components\PA\CRUD;

use App\Library\BaseInterfaces;
use App\Library\CRUD;
use App\Models;
use Illuminate\Http;
use Illuminate\Database;
use Illuminate\Support\Facades;

/**
 * Class Funds
 * @package App\Components\PA\CRUD
 */
class Funds extends CRUD\AbstractCUDWithRelatedUser implements BaseInterfaces\ArrayList
{
    const DEFAULT_ELEMENTS_PER_PAGE = 20;

    /**
     * @var array|null|string
     */
    protected $billId;

    /**
     * Funds constructor.
     * @param Http\Request $request
     */
    public function __construct(Http\Request $request)
    {
        parent::__construct($request);
        $this->billId = $request->input('bills_id');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getList() : array
    {
        $elementsPerPage = $this->request->input('paginate');

        if(empty($elementsPerPage)) {
            $elementsPerPage = self::DEFAULT_ELEMENTS_PER_PAGE;
        }

        $query = $this->SelectFilter()->orderBy('funds.date', 'desc');

        $totalSum = $this->totalSum($query);

        return ['paginate' =>$query->paginate($elementsPerPage), 'totalSum' => $totalSum];
    }

    public function getById(): Models\Funds
    {
        $query = $this->FundsSelectQuery()->where('funds.id', '=', $this->request->route('id'));

        return $query->first();
    }

    /**
     * @return bool
     */
    public function create() : bool
    {
        $funds = new Models\Funds();

        $funds->user_id = $this->userId;
        $funds->bills_id = $this->billId;
        $funds->rev = $this->request->input('rev');
        $funds->category_id = $this->request->input('category_id');
        $funds->sum = $this->helpers->money()->convertSumWithCommaToSumWithDot($this->request->input('sum'));
        $funds->cause = $this->request->input('cause');
        $funds->date = $this->request->input('date');

        Facades\DB::beginTransaction();

        try {
            $saved = (bool) $funds->save();
            $this->billsCalculate($funds->rev, $funds->sum);
            Facades\DB::commit();
        }
        catch (\Throwable $e) {
            Facades\DB::rollback();
            logger($e->getMessage(), [$e]);
            return false;
        }
        return $saved;

    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        Facades\DB::beginTransaction();
        $funds = Models\Funds::whereId($this->request->input('funds_id'))->first();

        if(empty($funds)) {
            return false;
        }

        /**
         * Получаем сторону изменения (доход или расход), в зависимости от этого, отнимаем старую сумму или
         * прибавляем ее. Далее записываем новые данные в модель, а потом считаем заново, уже с новой суммой.
         */
        $rev = $this->request->input('rev');

        try {
            if($rev == 1) {
                $this->billsCalculate(2, $funds->sum);
            }
            else if($rev == 2) {
                $this->billsCalculate(1, $funds->sum);
            }

            $funds->rev = $rev;
            $funds->category_id = $this->request->input('category_id');
            $funds->sum = $this->helpers->money()->convertSumWithCommaToSumWithDot($this->request->input('sum'));
            $funds->cause = $this->request->input('cause');
            $funds->date = $this->request->input('date');

            $saved = (bool) $funds->save();
            $this->billsCalculate($funds->rev, $funds->sum);
            Facades\DB::commit();
        }
        catch (\Throwable $e) {
            Facades\DB::rollback();
            logger($e->getMessage(), [$e]);
            return false;
        }


        return $saved;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete() : bool
    {
        Facades\DB::beginTransaction();
        $funds = Models\Funds::whereId($this->request->input('funds_id'))->first();

        if(empty($funds)) {
            return false;
        }

        $this->billId = $funds->bills_id;

        /**
         * Если удаляем, то берем значение, что это было расход или доход.
         * Сразу отнимаем или вычитаем из счета, а потом удаляем
         */
        $rev = $funds->rev;

        try {
            if($rev == 1) {
                $this->billsCalculate(2, $funds->sum);
            }
            else if($rev == 2) {
                $this->billsCalculate(1, $funds->sum);
            }
            $deleted = $funds->delete();
            Facades\DB::commit();
        }
        catch (\Throwable $e) {
            logger($e->getMessage(), [$e]);
            Facades\DB::rollback();
            return false;
        }

        return $deleted;
    }

    /**
     * @param $query
     * @return int
     */
    private function totalSum($query)
    {
        $result = $query->get();

        $sum = 0;

        foreach ($result as $element) {
            $sum += $element->sum;
        }

        return $sum;

    }

    /**
     * @return Database\Query\Builder
     * @throws \Exception
     */
    private function SelectFilter()
    {
        $rev = $this->request->input('rev');
        $billId = $this->request->input('bills_id');
        $categoryId = $this->request->input('category_id');
        $date = ['start' => $this->request->input('date_start'), 'end' => $this->request->input('date_end')];
        $sum = $this->request->input('sum');

        $query = $this->FundsSelectQuery();

        if(!empty($rev)) {
            $query->where('funds.rev', '=', $rev);
        }

        if(!empty($billId)) {
            $query->where('funds.bills_id', '=', $billId);
        }

        if(!empty($categoryId)) {
            $query->where('funds.category_id', '=', $categoryId);
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

            $dayPrevious = !empty($dateStart) ? $dateStart->format('Y-m-d 00:00:00') : 0;
            $dayNext = $dateEnd->format('Y-m-d 23:59:59');

            $query->whereBetween('funds.date', [$dayPrevious, $dayNext]);
        }

        if(!empty($sum)) {
            $query->where('funds.sum', '=', $sum);
        }

        return $query;

    }

    /**
     * @return Models\Funds|Database\Query\Builder
     */
    private function FundsSelectQuery()
    {
        return Models\Funds::select('funds.*', 'bills.name as bills_name', 'rev_categories.name as category_name')
            ->leftJoin('bills', 'funds.bills_id', '=', 'bills.id')
            ->leftJoin('rev_categories', 'funds.category_id', 'rev_categories.id')
            ->where('funds.user_id', '=', $this->userId);
    }

    /**
     * @param $rev
     * @param $sum
     */
    private function billsCalculate($rev, $sum)
    {
        $bill = Models\Bills::whereId($this->billId)->first();

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
