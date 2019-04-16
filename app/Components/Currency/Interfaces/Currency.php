<?php

namespace App\Components\Currency\Interfaces;


interface Currency
{
    public function getCurrentCurrency($currencyCode) : float;

    public function getCurrencyInfo($currencyCode) : array;

    public function getSupportedCurrencies() : array;
}