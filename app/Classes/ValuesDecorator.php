<?php

namespace App\Classes;

use App\References\ReferenceGear;
use Illuminate\Support\Arr;

class ValuesDecorator
{
    const ID_AUTO_RU = 1;
    const ID_AVITO_RU = 2;
    const ID_DROM_RU = 3;

    /**
     * Return auto modification as a string
     *
     * @var array $entity
     *
     * @return string
     */
    static public function getModificationString(array $entity): string
    {
        $gearString = app(ReferenceGear::class)->getShort($entity['gear']);
        return $entity['volume'] . ' ' . ($gearString !== null ? $gearString : '') . " ({$entity['power']} л.с.)" . ($entity['drive'] === 'awd' ? ' 4WD' : '');
    }

    /**
     * Return Auto.ru status as a string
     *
     * @var array $entity
     *
     * @return string
     */
    static public function getPublishedAutoRuString(array $entity): string
    {
        return Arr::first($entity['stockPublications'], function ($value) {
            return $value['propPublicationId'] === self::ID_AUTO_RU && $value['publish'];
        }) ? 'Опубликовано' : 'Не опубликовано';
    }

    /**
     * Return Avito.ru status as a string
     *
     * @var array $entity
     *
     * @return string
     */
    static public function getPublishedAvitoRuString(array $entity): string
    {
        return Arr::first($entity['stockPublications'], function ($value) {
            return $value['propPublicationId'] === self::ID_AVITO_RU && $value['publish'];
        }) ? 'Опубликовано' : 'Не опубликовано';
    }

    /**
     * Return Drom.ru status as a string
     *
     * @var array $entity
     *
     * @return string
     */
    static public function getPublishedDromRuString(array $entity): string
    {
        return Arr::first($entity['stockPublications'], function ($value) {
            return $value['propPublicationId'] === self::ID_DROM_RU && $value['publish'];
        }) ? 'Опубликовано' : 'Не опубликовано';
    }

    /**
     * Format date string
     *
     * @var string $date
     *
     * @return string
     */
    static public function formatDate(string $date): string
    {
        return date('d.m.Y', strtotime($date));
    }
}
