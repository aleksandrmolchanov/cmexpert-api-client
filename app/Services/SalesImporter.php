<?php

namespace App\Services;

class SalesImporter extends Importer
{
    function __construct() {
        parent::__construct();

        $this->requestUrl = config('api.server.urls.stock');
        $this->requestFilters = [
            'filter[stockState]' => 'out',
            'filter[saleAt][gte]' => $this->getDateAgoAsString()
        ];

        //var_dump($this->requestFilters); exit;
    }

    /**
     * Get date two month ago as a string
     *
     * @return string
     */
    public function getDateAgoAsString(): string
    {
        return date("Y-m-01\T00:00:00+00:00", strtotime("-1 month"));
    }

    /**
     * Process single page from api
     *
     * @return void
     */
    public function processData($data)
    {

    }
}
