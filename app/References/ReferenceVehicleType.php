<?php

namespace App\References;

class ReferenceVehicleType extends Reference
{
    protected array $references = [
        'pc' => 'Легковой',
        'lcv' => 'Легкий коммерческий',
        'moto' => 'Мотоцикл',
        'hgv' => 'Грузовой',
        'bus' => 'Автобус',
        'ttr' => 'Седельный тягач',
        'agm' => 'Сельскохозяйственная',
        'com' => 'Строительная',
        'alr' => 'Автопогрузчик',
        'trc' => 'Автокран',
        'sll' => 'Самопогрузчик',
        'exc' => 'Экскаватор'
    ];
}
