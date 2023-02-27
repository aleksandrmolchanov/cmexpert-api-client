<?php

namespace App\References;

class ReferenceInChannel extends Reference
{
    protected array $references = [
        'new' => 'Продажа новых',
        'used' => 'Продажа бу',
        'parallelimport' => 'Параллельный импорт',
        'incalls' => 'Входящие звонки',
        'pedestrian' => 'Пешеходы',
        'outcalls' => 'Исходящие звонки',
        'offers' => 'Объявления',
        'internetrequest' => 'Интернет-запрос',
        'internalpark' => 'Внутренний парк',
        'corppark' => 'Корп. парк',
        'service' => 'Сервис',
        'fi' => 'FI, из отдела страхования или кредитования',
        'action' => 'Акция',
        'painter' => 'МКЦ',
        'commission' => 'Комиссия',
        'directbuyback' => 'Прямой выкуп',
        'urgentbuyback' => 'Срочный выкуп',
        'distributorspark' => 'Парк дистрибьютора',
        'auction' => 'Аукцион',
        'buyoutoutdoors' => 'Выкуп выезд',
        'buyoutindoors' => 'Выкуп в салоне',
        'buyoutfromsecondhanddealer' => 'Выкуп у перекупа',
        'buyoutcme' => 'Закупка с рынка (CME)'
    ];
}
