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
}
