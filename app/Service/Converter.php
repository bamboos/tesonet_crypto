<?php

namespace App\Service;

use App\Asset;
use App\Contracts\Exchanger;

class Converter
{
    private Exchanger $exchanger;

    public function __construct(Exchanger $exchanger)
    {
        $this->exchanger = $exchanger;
    }

    /**
     * @param Asset|Asset[] $asset
     * @param $currencyTo
     * @return float|int
     */
    public function convert($asset, string $currencyTo)
    {
        $calc = fn ($asset) => $asset['value'] * $this->exchanger->getExchangeRatio(
            $asset['currency'],
            $currencyTo
        );

        return $asset instanceof Asset
            ? $calc($asset->toArray())
            : array_sum(array_map(
                fn ($asset) => $calc($asset),
                $asset->toArray()
            ));
    }
}
