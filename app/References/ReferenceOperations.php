<?php

namespace App\References;

class ReferenceOperations extends Reference
{
    protected array $references = [
        'reserve' => 'Резерв',
        'repair' => 'Ремонт',
        'fixprice' => 'Фиксированная цена',
        'prepare' => 'Предпродажная подготовка',
        'logistics' => 'Логистика',
        'legalproblems' => 'Юридические проблемы'
    ];

    public function getCodes(): array
    {
        return array_keys($this->references);
    }
}
