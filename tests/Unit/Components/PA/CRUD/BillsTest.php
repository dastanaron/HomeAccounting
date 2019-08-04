<?php


namespace Tests\Unit\Components\PA\CRUD;

use Tests\Unit\DB;
use App\Models;

class BillsTest extends DB\AbstractDataBaseTest
{
    use DB\createUserTrait;

    /**
     * @var int
     */
    private $userId;

    private $request;

    protected function setUp()
    {
        parent::setUp();
        $this->userId = $this->createUser();
        $this->request = new Mock\BillsRequest();
    }

    public function testCreate()
    {
        $this->request->setComment('test');
        $this->request->setName('test bill');
        $this->request->setSum('1000');
        $this->request->setCurrency('643');

        $crud = new Mock\BillsCrud($this->request);
        $this->assertTrue($crud->create());
        $billModel = $crud->getList()->first();
        /**@var Models\Bills $billModel */
        $this->assertSame($billModel->name, 'test bill');
        $this->assertSame($billModel->comment, 'test');
        $this->assertEquals($billModel->sum, 1000);
        $this->assertEquals($billModel->currency, 643);
    }

    public function testUpdate()
    {
        $this->testCreate();
        $this->request->setBillId('1');
        $this->request->setName('test 2');
        $this->request->setDeadline('2019-12-19');
        $this->request->setSum('500');
        $crud = new Mock\BillsCrud($this->request);
        $this->assertTrue($crud->update());
        $billModel = $crud->getList()->first();
        /**@var Models\Bills $billModel */
        $this->assertEquals($billModel->name, 'test 2');
        $this->assertEquals($billModel->deadline, '2019-12-19 00:00:00');
        $this->assertEquals($billModel->sum, '500');
    }

    public function testDelete()
    {
        $this->testCreate();
        $this->request->setBillId('1');
        $crud = new Mock\BillsCrud($this->request);
        $this->assertTrue($crud->delete());
        $billModel = $crud->getList()->first();
        $this->assertNull($billModel);
    }

    public function tearDown()
    {
        unset($this->request);
        parent::tearDown();
    }

}