<?php


namespace App\Integrations\nalogRu\Library;

use GuzzleHttp;
use App\Integrations\nalogRu;

class API
{
    const URL = 'https://proverkacheka.nalog.ru:9999';
    const API_VERSION = 'v1';
    const MOBILE_API = 'mobile';

    private $client;

    /**
     * @param string $email
     * @param string $name
     * @param string $phone format +79991234567
     * @return string
     */
    public function register(string $email, string $name, string $phone)
    {
        $command = 'users/signup';
        $body = [
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
        ];
        $this->client()->parameters()->json($body);
        return $this->client()->request($command, CLIENT::METHOD_POST);
    }

    /**
     * @param string $phone format +79991234567
     * @param string $smsCode
     * @return string
     */
    public function login(string $phone, string $smsCode)
    {
        $command = 'users/login';
        $this->client()->parameters()->auth($phone, $smsCode)->headers(['User-Agent' => Client::USER_AGENT]);
        return $this->client()->request($command, Client::METHOD_GET);
    }

    /**
     * @param string $phone format +79991234567
     * @return string
     */
    public function restorePassword(string $phone)
    {
        $command = 'users/restore';
        $this->client()->parameters()->json(['phone' => $phone]);
        return $this->client()->request($command, CLIENT::METHOD_POST);
    }

    /**
     * @param string $barcodeString
     * @param string $phone
     * @param string $smsCode
     * @return string
     */
    public function checkExist(string $barcodeString, string $phone, string $smsCode)
    {
        $parsedObject = (new BarcodeParser())->simpleParse($barcodeString);

        $command = "/ofds/*/inns/*/fss/{$parsedObject->fiscalNumber}/operations/{$parsedObject->checkType}/tickets/{$parsedObject->fiscalDocument}?fiscalSign={$parsedObject->fiscalSign}&date={$parsedObject->date->format('Y-m-dTH:i:s')}&sum={$parsedObject->sum}";
        $this->client()->parameters()->auth($phone, $smsCode)->headers(['User-Agent' => Client::USER_AGENT]);
        return $this->client()->request($command, CLIENT::METHOD_GET);
    }

    /**
     * @return Client
     */
    private function client()
    {
        $baseUri = self::URL . '/' . self::API_VERSION . '/' . self::MOBILE_API . '/';

        if ($this->client === null) {
            $this->client = new Client($baseUri);
        }
        return $this->client;
    }
}