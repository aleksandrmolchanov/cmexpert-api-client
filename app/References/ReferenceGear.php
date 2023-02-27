<?php

namespace App\References;

class ReferenceGear extends Reference
{
    protected array $references = [
        'mt' => [
            'short' => 'МТ',
            'full' => 'Механическая'
        ],
        'at' => [
            'short' => 'AТ',
            'full' => 'Автоматическая'
        ],
        'cvt' => [
            'short' => 'CVT',
            'full' => 'Вариатор'
        ],
        'amt' => [
            'short' => 'Робот',
            'full' => 'Роботизированная'
        ]
    ];

    public function getShort($key, $default = null) {
        $value = data_get($this->references, $key . '.short');
        return $value ?? $default;
    }

    public function getFull($key, $default = null) {
        $value = data_get($this->references, $key . '.full');
        return $value ?? $default;
    }
}
