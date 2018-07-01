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
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }
}