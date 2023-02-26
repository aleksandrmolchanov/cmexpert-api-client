<?php

namespace App\Services;

class NumbersImporter extends Importer
{
    function __construct() {
        parent::__construct();

        $this->requestUrl = config('api.server.urls.stock');
        $this->requestFilters = [
            'filter[stockState]' => 'in'
        ];
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
