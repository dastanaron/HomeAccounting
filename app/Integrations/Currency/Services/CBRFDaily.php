<?php

declare(strict_types = 1);

namespace App\Integrations\Currency\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

/**
 * Центральный банк РФ, API
 */
class CBRFDaily
{

    /**
     * @var GuzzleClient | null
     */
    protected $client;

    /**
     * @var string
     */
    private $APIUrl = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @var string
     */
    private $dateFormat = 'd/m/Y';

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var CurrencyDataProvider[]
     */
    private $currencies;

    /**
     * CBRFDaily constructor.
     * @param string $date
     * @throws \Exception
     */
    public function __construct(string $date = '')
    {
        if(empty($date)) {
            $this->date = new \DateTime($date);
        }
        else {
            $this->date = new \DateTime('now');
        }
    }

    /**
     * @return CurrencyDataProvider[]
     *
     */
    public function getCurrenciesList() : array
    {
        $xml = new \SimpleXMLElement($this->getDailyData());

        $array = [];

        foreach ($xml as $element) {
            try {
                $array[] = (new CurrencyDataProvider())->buildProviderByXml($element);
            }
            catch (\Exception $e) {
                Log::debug('Не удается спарсить данные по валюте', __METHOD__);
            }

        }

        $this->currencies = $array;

        return $this->currencies;
    }

    /**
     * @return string
     */
    protected function getDailyData(): string
    {
        return $this->getClient()->get($this->APIUrl . '?date_req=' . $this->date->format($this->dateFormat))->getBody()->getContents();
    }

    /**
     * @return GuzzleClient
     */
    protected function getClient(): GuzzleClient
    {
        if(!($this->client instanceof GuzzleClient)) {
            $this->client = new GuzzleClient();
        }

        return $this->client;
    }
}