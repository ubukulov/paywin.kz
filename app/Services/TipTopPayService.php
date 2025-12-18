<?php

namespace App\Services;

use GuzzleHttp\Client;
class TipTopPayService
{
    private Client $client;
    private string $base;

    public function __construct()
    {
        $this->base = env('TIPTOPPAY_URL');

        $this->client = new Client([
            'base_uri' => $this->base,
            'verify' => false,
            'timeout' => 10,
        ]);
    }

    public function payment($array)
    {
        try {
            $username = env('TIPTOPPAY_PUBLIC_ID');
            $password = env('TIPTOPPAY_PASSWORD');

            $data = [
                "Amount" => $array['amount'],
                "InvoiceId" => $array['orderId'],
                "IpAddress" => request()->ip(),
                "CardCryptogramPacket" => $array['cryptogram']
            ];

            $response = $this->client->request('POST', 'payments/cards/charge', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
                'auth' => [$username, $password]
            ]);

            return json_decode($response->getBody(), true);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
