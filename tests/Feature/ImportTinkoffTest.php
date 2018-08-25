<?php

namespace Tests\Feature;

use App\Funds;
use App\Http\helpers\FundsHelper;
use App\Modules\Import\Ofx\Tinkoff\TinkoffMatches;
use App\Modules\Import\Ofx\Tinkoff\TinkoffTransactionObject;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Import\Ofx\OfxObject;
use App\Modules\Import\Ofx\OfxParser;
use App\Modules\Import\Ofx\Tinkoff\TinkoffObject;


class ImportTinkoffTest extends TestCase
{

    public function testObjects()
    {
        $ofx = OfxParser::getInstance()->openFile(__DIR__ . '/operations.ofx');

        $tinkoffObject = new TinkoffObject($ofx);

        $tinkoffTransactions = $tinkoffObject->getTransactions();

        $this->assertTrue(count($tinkoffTransactions) > 0, 'Пустые транзакции');
    }

    /**
     * Этот тест только, если есть данные в базе с определенным счетом и пользователем
     */
    public function testGetDBTransactions()
    {
        $file = __DIR__ . '/operations.ofx';

        $this->assertFileExists($file, 'Нет файла для парсинга');

        $tinkoffMatches = new TinkoffMatches(2, $file, 1);

        $coincidences = $tinkoffMatches->getCoincidences();

        $newTransactions = $tinkoffMatches->getNewTransactions();

        $this->assertTrue(is_array($coincidences) && !empty($coincidences), 'Не содержит совпадений по транзакциям');

        $this->assertTrue(is_array($newTransactions) && !empty($newTransactions), 'Не найдено новых транзакций');

    }
}
