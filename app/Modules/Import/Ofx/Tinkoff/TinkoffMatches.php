<?php

namespace App\Modules\Import\Ofx\Tinkoff;

use App\Modules\Import\Ofx\OfxParser;
use App\Funds;
use Carbon\Carbon;

class TinkoffMatches
{

    /**
     * Связка со счетом.
     * Счет с которым сравниваем выгрузку
     * @var integer
     */
    protected $billsId;
    /**
     * Путь к файлу, для распарсивания
     * @var string
     */
    protected $file;
    /**
     * Связка с пользователем, чемй счет
     * @var integer
     */
    protected $userId;

    /**
     * Массив совпадений с данными в базе
     * @var array
     */
    protected $arrayOfCoincidences = [];

    /**
     * Транзакции, которые есть в файле, но нет в базе
     * @var array
     */
    protected $noTransactionsFound = [];

    /**
     * TinkoffMatches constructor.
     * Должен получить все необходимые связки с пользователем и счетом, а также путь к загруженному файлу
     * @param int $billsId
     * @param string $file
     * @param int $userId
     */
    public function __construct(int $billsId, string $file, int $userId)
    {
        $this->billsId = $billsId;
        $this->file = $file;
        $this->userId = $userId;

        $this->searchCoincidences();
    }


    /**
     * @return $this
     */
    public function searchCoincidences(): TinkoffMatches
    {
        $ofx = OfxParser::getInstance()->openFile($this->file);

        $tinkoffObject = new TinkoffObject($ofx);

        $fundsTinkoff = Funds::where('bills_id', '=', $this->billsId)
            ->where('user_id', '=', $this->userId)
            ->whereBetween('date', $tinkoffObject->getDateRange());

        $tinkoffTransactions = $tinkoffObject->getTransactions();

        $arrayOfCoincidences = [];
        $noTransactionsFound = [];

        foreach($fundsTinkoff->get() as $row) {

            foreach($tinkoffTransactions as $transaction) {

                $rowDate = Carbon::parse($row->date);

                /**
                 * Условие совпадения. Сходятся по сумме, что оба расход
                 * @var TinkoffTransactionObject $transaction
                 */

                if(
                    $transaction->amount === $row->sum
                    && $rowDate->toDateString() == $transaction->date->toDateString()
                    && ($transaction->type === TinkoffTransactionObject::TYPE_DEBIT && $row->rev === TinkoffTransactionObject::TYPE_BASE_DEBIT)
                    || ($transaction->type === TinkoffTransactionObject::TYPE_CREDIT && $row->rev === TinkoffTransactionObject::TYPE_BASE_CREDIT)
                ) {
                    $arrayOfCoincidences[] = ['in_base' => $row, 'in_file' => $transaction];
                }
                else {
                    $noTransactionsFound[] = $transaction;
                }
            }

        }

        $this->arrayOfCoincidences = $arrayOfCoincidences;
        $this->noTransactionsFound = $noTransactionsFound;

        return $this;

    }

    /**
     * @return array
     */
    public function getCoincidences(): array
    {
        return $this->arrayOfCoincidences;
    }

    /**
     * @return array
     */
    public function getNewTransactions(): array
    {
        return $this->noTransactionsFound;
    }
}