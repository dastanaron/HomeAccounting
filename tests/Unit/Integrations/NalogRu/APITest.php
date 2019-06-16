<?php

namespace Tests\Unit\Integrations\NalogRu;

use Tests\TestCase;
use App\Integrations\nalogRu\Library;
use App\Library\Utilities;

class APITest extends TestCase
{

    /**
     * @var Library\API
     */
    private $api;

    protected function setUp()
    {
        $this->api = new Library\API();
        parent::setUp();
    }

    public function testRegister()
    {
        $answer = $this->api->register(env('TEST_EMAIL'), 'dastanaron', env('TEST_PHONE'));

        $decodedAnswer = Utilities\Json::decode($answer);

        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(409, $decodedAnswer['code']);
    }

    public function testLogin()
    {
        $answer = $this->api->login(env('TEST_PHONE'), env('TEST_SMS_CODE'));
        $decodedAnswer = Utilities\Json::decode($answer);

        if (count($decodedAnswer) === 2) {
            $this->assertTrue(array_key_exists('email', $decodedAnswer));
            return;
        }
        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(403, $decodedAnswer['code']);
    }

    /**
     * @dataProvider existCheckDataProvider
     * @param $barcodeString
     */
    public function testExistCheck($barcodeString)
    {
        $answer = $this->api->checkExist($barcodeString, env('TEST_PHONE'), env('TEST_SMS_CODE'));
        $decodedAnswer = Utilities\Json::decode($answer);
        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(406, $decodedAnswer['code']);
    }

    /**
     * @dataProvider existCheckDataProvider
     * @param $barcodeString
     */
    public function testGetDetailInfoAboutCheck($barcodeString)
    {
        $answer = $this->api->getCheckDetailInfo($barcodeString, env('TEST_PHONE'), env('TEST_SMS_CODE'));
        $decodedAnswer = Utilities\Json::decode($answer);
        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(404, $decodedAnswer['code']);
    }

    /**
     * @return array[int]array[int]string
     */
    public function existCheckDataProvider()
    {
        return [
            ['t=20190613T132300&s=524.39&fn=9289000100393237&i=20509&fp=2249765769&n=1'],
            ['t=20190607T1411&s=820.27&fn=9289000100306511&i=3233&fp=2148089414&n=1']
        ];
    }
}
