<?php

namespace App\Classes;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class SendPulse
{
    private Client $client;
    private string $base;

    public function __construct()
    {
        $this->base = rtrim(env('SENDPULSE_API'), '/').'/';

        $this->client = new Client([
            'base_uri' => $this->base,
            'verify' => false,
            'timeout' => 10,
            'headers'  => [
                'Authorization' => 'Bearer ' . env('SENDPULSE_SECRET'),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function sendEmail($mailSubject, $templateId, User $user, $data)
    {
        try {
            $response = $this->client->post('smtp/emails', [
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

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info("SendPulse Success", $result);
        } catch (\Exception $e) {
            Log::error("SendPulse Send Email Error: " . $e->getMessage());
        }
    }
}
