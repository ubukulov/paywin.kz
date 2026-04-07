<?php

namespace App\Classes;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use Sendpulse\RestApi\ApiClientException;

class SendPulse
{
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function sendEmail($mailSubject, $htmlMessage, User $user)
    {
        try {
            $smtpSendMailResult = $this->apiClient->post('smtp/emails', [
                'email' => [
                    'html' => base64_encode($htmlMessage),
                    'text' => 'text',
                    'subject' => $mailSubject,
                    'from' => [
                        'name' => 'no-reply',
                        'email' => 'no-reply@paywin.kz',
                    ],
                    'to' => [
                        [
                            'name' => $user->name ?? 'no name',
                            'email' => $user->email,
                        ]
                    ],
                    /*'bcc' => [
                        [
                            'name' => 'bcc',
                            'email' => 'bcc@test.com',
                        ]
                    ],
                    'attachments_binary' => [
                        'attach_image.jpg' => base64_encode(file_get_contents('../storage/attach_image.jpg'))
                    ],*/
                ]
            ]);

            var_dump($smtpSendMailResult);
        } catch (ApiClientException $e) {
            Log::error("SendPulse Send Email Error: " . $e->getMessage());
        }
    }
}
