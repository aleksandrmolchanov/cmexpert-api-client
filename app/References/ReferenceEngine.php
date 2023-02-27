<?php

namespace App\References;

class ReferenceEngine extends Reference
{
    protected array $references = [
        'petrol' => 'Бензиновый',
        'diesel' => 'Дизельный',
        'electric' => 'Электрический',
        'gas' => 'Газовый',
        'hybrid' => 'Гибридный'
    ];
}
