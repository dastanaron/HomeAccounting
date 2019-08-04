<?php

namespace Tests\Unit\Integrations\NalogRu;

use App\Integrations\nalogRu\Library;
use App\Library\Utilities;
use Tests\Unit;
use App\Integrations\nalogRu\Objects;

class CRUDTest extends Unit\DB\AbstractDataBaseTest
{
    use Unit\DB\createUserTrait;

    /**
     * @var int
     */
    private $userId;

    protected function setUp()
    {
        parent::setUp();
        $this->userId = $this->createUser();
    }

    public function testCRUD()
    {
        $meta = $this->createMeta();
        $crud = new Library\CRUD();
        $isCreated = $crud->create($this->userId, $meta);

        $this->assertTrue($isCreated, 'model cannot be created');

        $integration = $crud->getIntegrationByUserId($this->userId);

        $this->assertSame(Library\CRUD::INTEGRATION_NAME, $integration->name);

        $metaFromDB = $crud->getMetaByUserId($this->userId);

        $this->assertInstanceOf(Objects\Meta::class, $metaFromDB);

        $this->assertEquals($meta, $metaFromDB);

        $metaFromDB->smsCode = 3582;
        $metaFromDB->email   = 'test2@test.test';

        $isUpdated = $crud->updateMeta($this->userId, $metaFromDB);
        $this->assertTrue($isUpdated, 'model cannot be updated');

        $metaFromDBAfterUpdated = $crud->getMetaByUserId($this->userId);
        $this->assertEquals(3582, $metaFromDBAfterUpdated->smsCode);
        $this->assertEquals('test2@test.test', $metaFromDBAfterUpdated->email);

        $isDeleted = $crud->delete($this->userId);
        $this->assertTrue($isDeleted, 'model cannot be deleted');
    }

    /**
     * @return Objects\Meta
     */
    private function createMeta(): Objects\Meta
    {
        $meta = new Objects\Meta();
        $meta->name = 'test name';
        $meta->phone = '+79134567890';
        $meta->email = 'test@email.ru';
        $meta->smsCode = null;
        return $meta;
    }
}
