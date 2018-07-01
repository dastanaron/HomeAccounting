<?php

namespace App\Modules\Import\Ofx\Tinkoff;


use App\Modules\Import\Ofx\OfxObject;

class TinkoffObject
{

    const BANK_ID = 'TINKOFF';

    /**
     * @var OfxObject
     */
    protected $ofx;

    /**
     * @var bool
     */
    protected $isValid = false;

    /**
     * @var array
     */
    protected $transactionObjectsCollection = [];

    /**
     * @var array
     */
    protected $dateRange = [];

    /**
     * TinkoffObject constructor.
     * @param OfxObject $ofxObject
     */
    public function __construct(OfxObject $ofxObject)
    {
        $this->ofx = $ofxObject;

        $this->isValid = $this->validate();

        $this->buildObjectCollection();
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $bankId = (string) $this->ofx->getObject()->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM->BANKID;

        $countTransactions = count($this->ofx->getObject()->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN);

        return $bankId === self::BANK_ID && $countTransactions > 0;
    }

    /**
     * @return $this
     */
    protected function buildObjectCollection(): TinkoffObject
    {
        if($this->isValid) {

            foreach($this->ofx->getObject()->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN as $transaction) {
                $this->transactionObjectsCollection[] = TinkoffTransactionObject::getInstance($transaction);
            }

        }

        $this->buildDateRange();

        return $this;
    }

    /**
     * Даты специально берутся с 0 по 23:59:59, потому как в ручном вводе, могут не совпадать по времени, но должны
     * входить в диапазон дат.
     * @return TinkoffObject
     */
    protected function buildDateRange(): TinkoffObject
    {
        $dates = [];

        foreach ($this->transactionObjectsCollection as $transaction) {
            /**
             * @var TinkoffTransactionObject $transaction
             */
            $dates[] = $transaction->date->toDateString();
        }

        $dates = array_unique($dates);

        sort($dates);

        $dateStart = $dates[0] . ' 00:00:00';

        $dateEnd = $dates[count($dates)-1] . ' 23:59:59';

        $this->dateRange = [$dateStart, $dateEnd];

        return $this;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactionObjectsCollection;
    }

    /**
     * @return array
     */
    public function getDateRange(): array
    {
        return $this->dateRange;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }
}