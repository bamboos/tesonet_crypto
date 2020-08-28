<?php

namespace App\Service\CryptonatorExchanger;

use App\Contracts\Exchanger;
use App\Service\Converter\ConversionException;

class CryptonatorAdaptor implements Exchanger
{
    private CryptonatorProxy $proxy;

    public function __construct(CryptonatorProxy $proxy, array $config)
    {
        $this->proxy = $proxy;
        $this->proxy->setConfig($config);
    }

    public function getExchangeRatio(string $currencyFrom, string $currencyTo): float
    {
        $json = $this->proxy->getRatio($currencyFrom, $currencyTo);

        if (!$json) {
            throw new ConversionException('Cryptonator service failed.');
        }

        if (empty($json['ticker']['price'])) {
            throw new ConversionException('Cryptonator service failed.');
        }

        return $json['ticker']['price'];
    }
}
