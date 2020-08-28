<?php

namespace Tests\Unit;

use App\Asset;
use App\Contracts\Exchanger;
use App\Service\Converter;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testConvert_SingleAsset(): void
    {
        $exchanger = $this->createMock(Exchanger::class);
        $exchanger
            ->method('getExchangeRatio')
            ->willReturn(2.5);

        $converter = new Converter($exchanger);
        $actual = $converter->convert(new Asset([
            'currency' => 'ETH',
            'value' => 200.00
        ]), 'USD');

        $this->assertEquals(500.00, $actual);
    }

    public function testConvert_MultipleAssets(): void
    {
        $exchanger = $this->createMock(Exchanger::class);
        $exchanger
            ->method('getExchangeRatio')
            ->willReturnCallback(fn (string $currency) => ['ETH' => 1.5, 'BTC' => 2.5][$currency]);

        $converter = new Converter($exchanger);
        $actual = $converter->convert(new Collection([
                new Asset([
                    'currency' => 'BTC',
                    'value' => 300.00
                ]),
                new Asset([
                    'currency' => 'ETH',
                    'value' => 200.00
                ])
            ]), 'USD');

        $this->assertEquals(1050.00, $actual);
    }
}
