<?php


namespace Tests\Unit\Components\PA\CRUD;

use Tests\Unit\DB;

class BillsTest extends DB\AbstractDataBaseTest
{
    use DB\createUserTrait;

    /**
     * @var int
     */
    private $userId;

    protected function setUp()
    {
        parent::setUp();
        $this->userId = $this->createUser();
    }

    public function testCreate() {
        $this->userId = 1;
    }

}