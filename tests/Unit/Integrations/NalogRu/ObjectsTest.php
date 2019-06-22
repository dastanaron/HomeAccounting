<?php

namespace Tests\Unit\Integrations\NalogRu;

use Tests\TestCase;
use App\Integrations\nalogRu;
use App\Library\Utilities;

class ObjectsTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @param $qrCode
     * @dataProvider qrCodesDataProvider
     */
    public function testBarcodeParser($qrCode)
    {
        $parsedCode = (new nalogRu\Library\BarcodeParser())->simpleParse($qrCode);
        $this->assertTrue($parsedCode->isValid());
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
