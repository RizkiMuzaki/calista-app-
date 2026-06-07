<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LouvinService
{
    public function createSubscription(array $payload): array
    {
        return $this->client()
            ->post($this->endpoint('/create-subscription'), $payload)
            ->throw()
            ->json();
    }

    public function createTransaction(array $payload): array
    {
        return $this->client()
            ->post($this->endpoint('/create-transaction'), $payload)
            ->throw()
            ->json();
    }

    public function checkStatus(string $transactionId): array
    {
        return $this->client()
            ->get($this->endpoint('/check-status'), ['id' => $transactionId])
            ->throw()
            ->json();
    }

    private function client(): PendingRequest
    {
        $apiKey = config('services.louvin.api_key');

        if (!$apiKey) {
            throw new RuntimeException('LOUVIN_API_KEY belum diatur di .env.');
        }

        return Http::acceptJson()
            ->asJson()
            ->withHeaders([
                'x-api-key' => $apiKey,
            ])
            ->timeout(20);
    }

    private function endpoint(string $path): string
    {
        return rtrim(config('services.louvin.base_url'), '/') . $path;
    }
}
