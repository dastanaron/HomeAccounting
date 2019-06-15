<?php


namespace App\Integrations\nalogRu\Library;

use GuzzleHttp;

class API
{
    const URL = 'https://proverkacheka.nalog.ru:9999';
    const API_VERSION = 'v1';
    const MOBILE_API = 'mobile';

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
        return $this->client()->request($command);
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

    public function isCheckExists()
    {
        //t=20190613T132300&s=524.39&fn=9289000100393237&i=20509&fp=2249765769&n=1
        $command = '/ofds/*/inns/*/fss/<номер ФН>/operations/<вид кассового чека>/tickets/<номер ФД>?fiscalSign=<номер ФПД>&date=2018-05-17T17:57:00&sum=3900';
    }

    /**
     * @return Client
     */
    private function client()
    {
        $baseUri = self::URL . '/' . self::API_VERSION . '/' . self::MOBILE_API . '/';
        return new Client($baseUri);
    }
}