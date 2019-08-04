<?php


namespace Tests\Unit\Components\PA\CRUD\Mock;

use App\Components\PA\CRUD;
use App\Library;

class BillsCrud extends CRUD\Bills
{
    protected $request;
    protected $userId;
    protected $helpers;

    public function __construct(BillsRequest $request)
    {
        $this->request = $request;
        $this->userId  = 1;
        $this->helpers = Library\Utilities\Helpers\Helpers::getInstance();
    }
}