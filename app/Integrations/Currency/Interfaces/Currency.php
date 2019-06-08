<?php

namespace App\Integrations\Currency\Interfaces;


interface Currency
{
    public function getCurrentCurrency($currencyCode) : float; //current exchange rate

    public function getCurrencyInfo($currencyCode) : array;

    public function getSupportedCurrencies() : array;
}
