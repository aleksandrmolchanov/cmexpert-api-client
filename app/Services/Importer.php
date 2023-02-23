<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Importer
{
    /*
     * API access token
     */
    public string $accessToken;

    /*
     * Request retry options
     */
    public array $retry;

    function __construct()
    {
        $this->retry = [
            'times' => config('api.connection.retries.times'),
            'sleep' => config('api.connection.retries.sleep')
        ];

        $this->updateAccessToken();
    }

    /**
     * Updates access token for instance
     *
     * @return void
     */
    public function updateAccessToken(): void
    {
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Gets access token for authentication
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        $response = Http::retry($this->retry['times'], $this->retry['sleep'])->post(config('api.server.urls.auth'), [
            'grant_type' => 'client_credentials',
            'client_id' => config('api.client.id'),
            'client_secret' => config('api.client.secret')
        ]);

        return $response->json('access_token');
    }
}
