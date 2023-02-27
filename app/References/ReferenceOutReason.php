<?php

namespace App\References;

class ReferenceOutReason extends Reference
{
    protected array $references = [
        'return' => 'Возврат',
        'selling' => 'Продажа',
        'other' => 'Другая'
    ];
}
