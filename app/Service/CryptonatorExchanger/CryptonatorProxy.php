<?php

namespace App\Service\CryptonatorExchanger;

use Illuminate\Support\Facades\Http;

class CryptonatorProxy
{
    private array $config;

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getRatio(string $from, string $to)
    {
        $result = Http::get(
            $this->config['url'] . strtolower($from) . '-' . strtolower($to)
        );

        return !$result->failed() ? $result->json() : [];
    }
}
