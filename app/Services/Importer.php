<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Http\Client\PendingRequest;

class Importer
{
    /*
     * API access token
     */
    public string $accessToken;

    /*
     * Request url
     */
    public string $requestUrl;

    /*
     * Request filters
     */
    public array $requestFilters;

    /*
     * DB table name
     */
    public string $tableName;

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
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => config('api.client.id'),
            'client_secret' => config('api.client.secret')
        ];
        $response = Http::retry($this->retry['times'], $this->retry['sleep'])->post(config('api.server.urls.auth'), $params);

        return $response->json('access_token');
    }

    /**
     * Get data from api by pages and process it
     *
     * @var int $page
     * @var int $pages
     *
     * @return void
     */
    public function import(int $pageStart = 1, int $pages = INF)
    {
        $pagesCount = 0;
        $perPage = config('api.request.perPage');
        $page = $pageStart;

        do{
            $pageResponse = $this->getData($this->requestUrl, array_merge(['page' => $page, 'perPage' => $perPage], $this->requestFilters));
            if($pageResponse->successful())
            {
                $pagesCount = $pageResponse->header('x-pagination-page-count');
                $data = $this->processPage($pageResponse->json());
                $this->storeData($data);
            }
            $page++;
        }while($page <= $pagesCount && $page <= ($pages - 1 + $pageStart));
    }

    /**
     * Process single page from api
     *
     * @var string $url
     * @var array $params
     *
     * @return Response
     */
    public function getData(string $url, array $params = []): Response
    {
        return Http::withHeaders(['Authorization' => 'Bearer ' . $this->accessToken])->retry($this->retry['times'], $this->retry['sleep'], function (Exception $exception, PendingRequest $request) {
            if(strstr($exception->getMessage(), 'invalid credentials'))
            {
                $this->updateAccessToken();
                $request->withHeaders(['Authorization' => 'Bearer ' . $this->accessToken]);
            }
            return true;
        })->get($url, $params);
    }

    /**
     * Process single page from api
     *
     * @var array $data
     *
     * @return array
     */
    public function processPage(array $data): array
    {
        return [];
    }

    /**
     * Store data to database
     *
     * @return void
     */
    public function storeData($data)
    {
        DB::table($this->tableName)->insert($data);
    }
}
