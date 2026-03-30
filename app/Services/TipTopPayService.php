<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function confirm3DS($transactionId, $pares)
    {
        $username = env('TIPTOPPAY_PUBLIC_ID');
        $password = env('TIPTOPPAY_PASSWORD');

        $response = $this->client->request('POST', 'payments/cards/post3ds', [
            'auth' => [$username, $password],
            'json' => [
                'TransactionId' => $transactionId,
                'PaRes' => $pares,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getTransaction($transactionId)
    {
        // Документация TipTopPay: GET /payments/get
        // Используй Basic Auth (PublicID:SecretKey)
        $response = \Illuminate\Support\Facades\Http::withBasicAuth(
            env('TIPTOPPAY_PUBLIC_ID'),
            env('TIPTOPPAY_PASSWORD')
        )->post('https://api.tiptoppay.kz/payments/get', [
            'TransactionId' => $transactionId
        ]);

        return $response->json();
    }

    /**
     * Проверка статуса платежа по InvoiceId через API TipTopPay
     * * @param string $invoiceId
     * @return array ['success' => bool, 'amount' => float, 'transaction_id' => int, 'message' => string]
     */
    public function verifyByInvoice(string $invoiceId): array
    {
        try {
            // Запрашиваем список транзакций по нашему InvoiceId
            $response = Http::withBasicAuth(env('TIPTOPPAY_PUBLIC_ID'), env('TIPTOPPAY_PASSWORD'))
                ->post('https://api.tiptoppay.kz/payments/list', [
                    'InvoiceId' => $invoiceId,
                    'Date'      => now()->format('Y-m-d'),
                ]);

            $data = $response->json();

            // --- ВАЖНО: Смотри этот вывод в storage/logs/laravel.log ---
            Log::info("DEBUG TIPTOP: Invoice: {$invoiceId}");
            Log::info("DEBUG TIPTOP: Response Body:", (array)$data);

            if (!isset($data['Model']) || empty($data['Model'])) {
                // Если Model пустой, значит банк вообще не нашел транзакций с таким InvoiceId
                return [
                    'success' => false,
                    'message' => "Банк не нашел транзакций с InvoiceId: {$invoiceId}. Проверьте личный кабинет."
                ];
            }

            // 2. Ищем ЛЮБУЮ транзакцию из списка, чтобы понять, что вообще пришло
            $transactions = collect($data['Model']);

            // Ищем успешную (Completed или Authorized)
            $won = $transactions->first(function ($item) {
                return in_array($item['Status'], ['Completed', 'Authorized']);
            });

            if (!$won) {
                // Если не нашли успешную, давай вернем статус той, что нашли (например, Declined)
                $any = $transactions->first();
                return [
                    'success' => false,
                    'message' => "Транзакция найдена, но она не оплачена. Статус: " . ($any['Status'] ?? 'Unknown')
                ];
            }

            return [
                'success'        => true,
                'amount'         => (float) $won['Amount'],
                'transaction_id' => $won['TransactionId'],
                'raw_data'       => $won
            ];

        } catch (\Exception $e) {
            Log::error("TipTopPay Verify Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Системная ошибка при проверке'];
        }
    }
}
