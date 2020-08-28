<?php

namespace Tests\Unit;

use App\Service\Converter;
use App\Service\CryptonatorExchanger\CryptonatorAdaptor;
use App\Service\CryptonatorExchanger\CryptonatorProxy;
use PHPUnit\Framework\TestCase;

class CryptonatorAdaptorTest extends TestCase
{
    public function testGetExchangeRatio_Success(): void
    {
        $proxy = $this->createMock(CryptonatorProxy::class);
        $proxy
            ->method('getRatio')
            ->willReturn(['ticker' => ['price' => '1000.00']]);

        $adaptor = new CryptonatorAdaptor($proxy, []);
        $actual = $adaptor->getExchangeRatio('ETH', 'USD');

        $this->assertEquals(1000.00, $actual);
    }

    public function testConvert_FailedWithException_EmptyResult(): void
    {
        $this->expectException(Converter\ConversionException::class);
        $proxy = $this->createMock(CryptonatorProxy::class);
        $proxy
            ->method('getRatio')
            ->willReturn([]);

        $adaptor = new CryptonatorAdaptor($proxy, []);
        $adaptor->getExchangeRatio('ETH', 'USD');
    }

    public function testConvert_FailedWithException_EmptyPrice(): void
    {
        $this->expectException(Converter\ConversionException::class);
        $proxy = $this->createMock(CryptonatorProxy::class);
        $proxy
            ->method('getRatio')
            ->willReturn(['ticker' => ['price' => '']]);

        $adaptor = new CryptonatorAdaptor($proxy, []);
        $adaptor->getExchangeRatio('ETH', 'USD');
    }
}
