<?php

namespace App\Classes;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class SendPulse
{
    private static ?Client $client = null;

    /**
     * Инициализация или получение экземпляра Guzzle клиента
     */
    private static function getClient(): Client
    {
        if (self::$client === null) {
            $baseUri = rtrim(env('SENDPULSE_API'), '/') . '/';

            self::$client = new Client([
                'base_uri' => $baseUri,
                'verify'   => false,
                'timeout'  => 10,
                'headers'  => [
                    'Authorization' => 'Bearer ' . env('SENDPULSE_SECRET'),
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
            ]);
        }

        return self::$client;
    }

    /**
     * Статический метод для отправки Email
     */
    public static function sendEmail(string $mailSubject, $templateId, User $user, array $data): bool
    {
        try {
            $client = self::getClient();

            $response = $client->post('smtp/emails', [
                'json' => [
                    'email' => [
                        'template' => [
                            'id' => $templateId,
                            'variables' => $data
                        ],
                        'subject' => $mailSubject,
                        'from' => [
                            'name' => 'Paywin',
                            'email' => 'no-reply@paywin.kz',
                        ],
                        'to' => [
                            [
                                'name' => $user->name ?? 'Клиент',
                                'email' => $user->email,
                            ]
                        ],
                    ]
                ]
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            return isset($responseBody['result']) && $responseBody['result'] === true;

        } catch (\Exception $e) {
            Log::error("SendPulse Static Send Email Error: " . $e->getMessage());
            return false;
        }
    }
}
