<?php

namespace App\References;

use Illuminate\Support\Arr;

class Reference
{
    protected array $references = [];

    public function get($key, $default = null) {
        return Arr::get($this->references, $key, $default);
    }
}
