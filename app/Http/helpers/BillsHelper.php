<?php

namespace App\Http\helpers;


use App\Bills;
use Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class BillsHelper
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $user_id;

    /**
     * BillsHelper constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->user_id = Auth::user()->id;
        $this->request = $request;
    }

    /**
     * Function to Post request in controller
     * @return $this|bool
     */
    public function createBill()
    {

        $bill = new Bills();

        $bill->user_id = $this->user_id;

        try {
            $bill->name = $this->request->input('name');
            $bill->sum = $this->request->input('sum');
        }
        catch (\Exception $e) {
            return Response::json(['status' => 400, 'message' => 'bad request, name and sum must not be empty. Exception: ' . $e->getMessage() ])->setStatusCode(400);
        }

        $bill->deadline = $this->request->input('deadline');
        $bill->comment = $this->request->input('comment');


        return $bill->save();

    }

    /**
     * Function to PUT request in controller
     * @return $this|bool
     */
    public function setBill()
    {
        $bill = Bills::whereId($this->request->input('bill_id'))->where('user_id', '=', $this->user_id);

        if(empty($bill)) {
            return Response::json(['status' => 404, 'message' => 'User is not Found'])->setStatusCode(404);
        }

        $bill->name = $this->request->input('name');
        $bill->sum = $this->request->input('sum');
        $bill->deadline = $this->request->input('deadline');
        $bill->comment = $this->request->input('comment');

        return $bill->save();

    }

}