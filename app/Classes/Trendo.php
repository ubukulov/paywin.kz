<?php

namespace App\Classes;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
class Trendo
{
    private Client $client;
    private string $base;

    public function __construct()
    {
        $this->base = rtrim(env('TRENDO_API'), '/').'/';

        $this->client = new Client([
            'base_uri' => $this->base,
            'verify' => false,
            'timeout' => 60,
            'headers'  => [
                'auth' => env('TRENDO_TOKEN'),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function getCategories(): mixed
    {
        try {
            $response = $this->client->get('category/all_categories');

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }

    public function getSubCategories($categoryName)
    {
        try {
            $response = $this->client->get('category/sub_categories/' . $categoryName);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $exception) {
            Log::error("Error: " . $exception->getMessage());
        }
    }
}
