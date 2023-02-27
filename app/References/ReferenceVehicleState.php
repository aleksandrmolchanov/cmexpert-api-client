<?php

namespace App\References;

class ReferenceVehicleState extends Reference
{
    protected array $references = [
        'excellent' => 'Отличное состояние (A)',
        'good' => 'Хорошее состояние (B)',
        'average' => 'Средние состояние (C)',
        'bad' => 'Плохое состояние (D)',
        'broken' => 'Битый автомобиль'
    ];
}
