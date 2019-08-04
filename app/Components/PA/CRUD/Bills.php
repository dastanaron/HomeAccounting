<?php

declare(strict_types=1);

namespace App\Components\PA\CRUD;

use App\Library\BaseInterfaces;
use App\Library\CRUD;
use App\Models;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades;

/**
 * Class Bills
 * @package App\Components\PA\CRUD
 */
class Bills extends CRUD\AbstractCUDWithRelatedUser implements BaseInterfaces\CollectionList
{

    /**
     * @return Eloquent\Collection
     */
    public function getList() : Eloquent\Collection
    {
        return Models\Bills::whereUserId($this->userId)->get();
    }

    /**
     * @return bool
     */
    public function MoneyTransaction()
    {
        Facades\DB::beginTransaction();

        try {
            $billSource = Models\Bills::whereId($this->request->input('bill_source'))->where('user_id', '=', $this->userId)->first();
            $billDestination = Models\Bills::whereId($this->request->input('bill_destination'))->where('user_id', '=', $this->userId)->first();

            $sum = $this->helpers->money()->convertSumWithCommaToSumWithDot($this->request->input('sum'));

            if(empty($billSource) || empty($billDestination) || empty($sum)) {
                return false;
            }

            $billSource->sum = $billSource->sum - $sum;

            $billDestination->sum = $billDestination->sum + $sum;
            $billSource->save();
            $billDestination->save();
            Facades\DB::commit();
            return true;
        }
        catch (\Throwable $e) {
            Facades\DB::rollback();
            logger($e->getMessage(), [$e]);
            return false;
        }

    }

    /**
     * Function to Post request in controller
     * @return bool
     */
    public function create() : bool
    {
        $bill = new Models\Bills();

        $bill->user_id = $this->userId;

        try {
            $bill->name = $this->request->input('name');
            $bill->sum = $this->helpers->money()->convertSumWithCommaToSumWithDot($this->request->input('sum'));
        }
        catch (\Exception $e) {
            return false;
        }

        $bill->deadline = $this->request->input('deadline');
        $bill->comment = $this->request->input('comment');


        return $bill->save();
    }

    /**
     * Function to PUT request in controller
     * @return bool
     */
    public function update() : bool
    {
        $bill = Models\Bills::whereId($this->request->input('bill_id'))->where('user_id', '=', $this->userId)->first();

        if(empty($bill)) {
            return false;
        }

        $bill->name = $this->request->input('name');
        $bill->sum = $this->helpers->money()->convertSumWithCommaToSumWithDot($this->request->input('sum'));
        $bill->deadline = $this->request->input('deadline');
        $bill->currency = $this->request->input('currency');
        $bill->comment = $this->request->input('comment');

        return $bill->save();
    }

    /**
     * Function to DELETE request in controller
     * @return bool
     * @throws \Exception
     */
    public function delete() : bool
    {
        $bill = Models\Bills::whereId($this->request->input('bill_id'))->where('user_id', '=', $this->userId)->first();

        if(empty($bill)) {
            return false;
        }
        return $bill->delete();
    }

}