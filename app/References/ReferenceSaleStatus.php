<?php

namespace App\References;

class ReferenceSaleStatus extends Reference
{
    protected array $references = [
        'onsale' => 'В продаже',
        'offsale' => 'Не в продаже'
    ];
}
