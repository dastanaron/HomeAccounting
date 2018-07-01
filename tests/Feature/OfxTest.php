<?php

namespace Tests\Feature;

use App\Modules\Import\Ofx\OfxObject;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Import\Ofx\OfxParser;
use App\Modules\Import\Ofx\Tinkoff\TinkoffObject;
use App\Modules\Import\Ofx\Tinkoff\TinkoffTransactionObject;

class OfxTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOfxObject()
    {
        $ofx = OfxParser::getInstance()->openFile(__DIR__ . '/operations.ofx');

        $ofxIsOfxObject = $ofx instanceof OfxObject;

        $this->assertTrue($ofxIsOfxObject, 'Принадлежит классу ' . get_class($ofx));
    }

    public function testConvertParser()
    {
        OfxParser::setInputCharset('Windows-1251');
        OfxParser::setOutputCharset('UTF-8');

        $ofx = OfxParser::getInstance()->openFile(__DIR__ . '/operations.ofx');

        $tinkoffObject = new TinkoffObject($ofx);

        $this->assertTrue($tinkoffObject->isValid(), 'Ошибка не сработал конструктор '. TinkoffObject::class);

    }

    public function testOfxXmlObject()
    {
        $ofx = OfxParser::getInstance()->openFile(__DIR__ . '/operations.ofx');

        $objectsOfx = $ofx->getObject();

        $objectsOfxIsSimpleElements =  $objectsOfx instanceof \SimpleXMLElement;

        $this->assertTrue($objectsOfxIsSimpleElements, 'Принадлежит классу ' . get_class($objectsOfx));
    }


}
