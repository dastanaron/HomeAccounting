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

    /*public function testRegister()
    {
        $answer = $this->api->register(env('TEST_EMAIL'), 'dastanaron', env('TEST_PHONE'));

        $decodedAnswer = Utilities\Json::decode($answer);

        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(409, $decodedAnswer['code']);
    }*/

    public function testLogin()
    {
        $answer = $this->api->login(env('TEST_PHONE'), env('TEST_SMS_CODE'));
        $decodedAnswer = Utilities\Json::decode($answer);

        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(403, $decodedAnswer['code']);
    }

    public function testExistCheck()
    {
        $barcodeString = 't=20190613T132300&s=524.39&fn=9289000100393237&i=20509&fp=2249765769&n=1';
        $answer = $this->api->checkExist($barcodeString, env('TEST_PHONE'), env('TEST_SMS_CODE'));
        $decodedAnswer = Utilities\Json::decode($answer);
        $this->assertTrue(array_key_exists('code', $decodedAnswer));
        $this->assertSame(404, $decodedAnswer['code']);
    }
}
