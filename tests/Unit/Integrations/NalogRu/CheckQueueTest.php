<?php

namespace Tests\Unit\Integrations\NalogRu;

use Illuminate\Support;
use Illuminate\Database;
use Tests\TestCase;
use App\Models;

class CheckQueueTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @dataProvider qrCodesDataProvider
     */
    public function testInsert($qrCode)
    {
        $uuid = Support\Str::uuid()->toString();
        $model = $this->createModel($qrCode, $uuid);

        $wasSaved = $model->save();
        $this->assertTrue($wasSaved);
        $this->assertSame($uuid, $model->uuid);

        $query = Models\CheckQueue::where('uuid', '=', $uuid);
        $this->assertSame(1, $query->count());
        $this->assertSame(1, $query->delete());
    }

    /**
     * @dataProvider qrCodesDataProvider
     */
    public function testUniqueIndex($qrCode)
    {
        $uuid = Support\Str::uuid()->toString();
        $model = $this->createModel($qrCode, $uuid);

        $model->save();

        $doubleModel = $this->createModel($qrCode, $uuid);

        try {
            $doubleModel->save();
        }
        catch(\Exception $e) {
            $this->assertInstanceOf(Database\QueryException::class, $e);
        }

        $query = Models\CheckQueue::where('uuid', '=', $uuid);
        $this->assertSame(1, $query->count());
        $this->assertSame(1, $query->delete());
    }

    public function testAutoUuid()
    {
        $model = new Models\CheckQueue();
        $model->user_id = 1;
        $model->qrcode = 't=20190613T1762&s=500&fn=9289000100394547&i=20537&fp=2249765782&n=1';

        $this->assertTrue($model->save());
        $this->assertTrue($model->delete());
    }

    /**
     * @param string $qrCode
     * @param string $uuid
     * @return Models\CheckQueue
     */
    private function createModel(string $qrCode, string $uuid)
    {
        $model = new Models\CheckQueue();
        $model->uuid = $uuid;
        $model->user_id = 1;
        $model->qrcode = $qrCode;
        return $model;
    }

    /**
     * @return array[int]array[int]string
     */
    public function qrCodesDataProvider()
    {
        return [
            ['t=20190613T132300&s=524.39&fn=9289000100393237&i=20509&fp=2249765769&n=1'],
            ['t=20190607T1411&s=820.27&fn=9289000100306511&i=3233&fp=2148089414&n=1']
        ];
    }
}
