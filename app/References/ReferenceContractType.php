<?php

namespace App\References;

class ReferenceContractType extends Reference
{
    protected array $references = [
        'tradein' => 'Trade-in (обмен на новый или бу авто)',
        'tradeinnew' => 'Trade-in на новый (обмен на новый авто)',
        'tradeinused' => 'Trade-in на б/у (обмен на авто с пробегом)',
        'commission' => 'Комиссия',
        'buyout' => 'Выкуп',
        'internalpark' => 'Внутренний парк (продажа авто из внутреннего парка)',
        'return' => 'Возврат',
        'corppark' => 'Корпоративный парк',
    ];
}
