<?php

namespace App\Contracts;

interface Exchanger
{
    public function getExchangeRatio(string $currencyFrom, string $currencyTo): float;
}
