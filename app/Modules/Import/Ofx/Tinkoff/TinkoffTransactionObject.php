<?php

namespace App\Modules\Import\Ofx\Tinkoff;


use Carbon\Carbon;

class TinkoffTransactionObject
{

    const TYPE_DEBIT = 'DEBIT';
    const TYPE_CREDIT = 'CREDIT';

    const TYPE_BASE_DEBIT = 2;
    const TYPE_BASE_CREDIT = 1;

    /**
     * @var string
     */
    public $type;

    /**
     * @var Carbon
     */
    public $date;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var string
     */
    public $fitId;
    public $name;

    /**
     * @var string
     */
    public $category;

    /**
     * @var \stdClass
     */
    public $currency;


    /**
     * TinkoffTransactionObject constructor.
     * @param \SimpleXMLElement $xmlObject
     */
    public function __construct(\SimpleXMLElement $xmlObject)
    {
        $this->type = (string) $xmlObject->TRNTYPE;
        $this->date = $this->parseDate($xmlObject->DTPOSTED);
        $this->amount = $this->parseAmount($xmlObject->TRNAMT);
        $this->fitId = (string) $xmlObject->FITID;
        $this->name = (string) $xmlObject->NAME;
        $this->category = (string) $xmlObject->MEMO;

        $this->currency = new \stdClass();
        $this->currency->sym = (string) $xmlObject->CURRENCY->CURSYM;
        $this->currency->rate = (string) $xmlObject->CURRENCY->CURRATE;
    }

    /**
     * @param \SimpleXMLElement $xmlObject
     * @return TinkoffTransactionObject
     */
    public static function getInstance(\SimpleXMLElement $xmlObject): TinkoffTransactionObject
    {
        return new self($xmlObject);
    }

    /**
     * @param $date
     * @return Carbon
     */
    protected function parseDate($date): Carbon
    {
        $parsedTime = preg_replace('#(.+)\.(\d+)\[(.*)\]#', '$1', $date);

        return Carbon::parse($parsedTime);
    }

    /**
     * @param $amount
     * @return float
     */
    protected function parseAmount($amount): float
    {
        $amount = str_replace('-', '', $amount);

        $float = (float) $amount;

        return round($float, 2);
    }

}