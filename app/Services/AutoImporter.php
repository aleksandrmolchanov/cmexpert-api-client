<?php

namespace App\Services;

use App\Models\Number;
use App\References\ReferenceOperations;
use App\References\ReferenceSaleStatus;

class AutoImporter extends Importer
{
    function __construct() {
        parent::__construct();
    }

    /**
     * Get auto related operations
     *
     * @var array $entity
     *
     * @return array
     */
    public function getOperations(array $entity): array
    {
        $operationsUrl = str_replace(['{dealerId}', '{dmsCarId}'], [$entity['dealer']['id'], $entity['dmsCarId']], config('api.server.urls.operations'));

        return $this->getData($operationsUrl)->json();
    }

    /**
     * Get auto appraisal
     *
     * @var array $entity
     *
     * @return array|null
     */
    public function getAppraisal(array $entity): ?array
    {
        if(!isset($entity['appraisalCarId']))
        {
            return null;
        }

        $appraisalUrl = str_replace('{id}', $entity['appraisalCarId'], config('api.server.urls.appraisal'));
        $allAppraisals = $this->getData($appraisalUrl)->json();

        return $allAppraisals ? array_shift($allAppraisals) : null;
    }

    /**
     * Get auto placements
     *
     * @var array $entity
     *
     * @return array
     */
    public function getPlacements(array $entity): array
    {
        $placementsUrl = str_replace('{dmsCarId}', $entity['dmsCarId'], config('api.server.urls.placements'));
        $allPlacements = $this->getData($placementsUrl)->json();

        $placements = [];
        foreach($allPlacements as $placement)
        {
            $placements[$placement['siteSource']] = $placement;
        }
        return $placements;
    }

    /**
     * Sort auto related operations by types
     *
     * @var array $entity
     *
     * @return array
     */
    public function getOperationsByTypes(array $entity): array
    {
        $operationsByTypes = [];
        foreach (app(ReferenceOperations::class)->getCodes() as $operation)
        {
            $operationsByTypes[$operation] = [
                'now' => false,
                'days' => 0
            ];
        }

        $operations = $this->getOperations($entity);
        foreach($operations as $operation)
        {
            $dateStart = strtotime($operation['createdAt']);
            $dateEnd = strtotime($operation['plannedUntil']);
            $now = now()->timestamp;

            // Whether operation is active now
            $operationsByTypes[$operation['type']]['now'] = $dateEnd > $now;

            // Operation type days count
            $addDays = (int) floor((($dateEnd > $now ? $now : $dateEnd) - $dateStart) / (3600 * 24));
            $operationsByTypes[$operation['type']]['days'] += is_finite($addDays) ? $addDays : 0;
        }

        return $operationsByTypes;
    }

    /**
     * Get entity history
     *
     * @var array $entity
     *
     * @return array
     */
    public function getHistory(array $entity): array
    {
        $numbers = Number::select('Код объявления', 'Цена, ₽', 'Дата последнего изменения цены', 'Статус продажи', 'Точка продаж', 'Дата остатков')
            ->where('Код объявления', $entity['id'])->get();

        $history = [
            // First price
            'priceFirst' => count($numbers) ? $numbers[0]->{'Цена, ₽'} : null,

            // Price changes count
            'priceCount' => 0,

            // Days in numbers
            'onsaleDays' => 0,

            // Days at other dealers
            'otherDealersDays' => 0,

            // Date of moving
            'changeDealerAt' => null,

            // Previous dealer
            'lastDealer' => null
        ];

        $sellingPrice = $history['priceFirst'];

        if($numbers){
            foreach($numbers as $number)
            {
                // Price changes count
                if($sellingPrice !== $number['Цена, ₽']) {
                    if($sellingPrice !== null)
                    {
                        $history['priceCount']++;
                    }
                    $sellingPrice = $number['Цена, ₽'];
                }
                if($sellingPrice !== $entity['sellingPrice'])
                {
                    $history['priceCount']++;
                }

                // Days in numbers
                if($number['Статус продажи'] === app(ReferenceSaleStatus::class)->get('onsale'))
                {
                    $history['onsaleDays']++;
                }

                // If moves to another dealer
                if($entity['dealer']['id'] !== $number['dealerId']) {
                    // Date of moving
                    if($history['lastDealer'] !== $number['Точка продаж'])
                    {
                        $history['changeDealerAt'] = $number['Дата остатков'];
                    }

                    // Previous dealer
                    $history['lastDealer'] = $number['Точка продаж'];

                    // Days at other dealers
                    if($number['Статус продажи'] === app(ReferenceSaleStatus::class)->get('onsale'))
                    {
                        $history['otherDealersDays']++;
                    }
                }

                $history['onsaleDays']++;
            };
        }

        if(!$numbers && $entity['inStockPeriod'] > 0 && $entity['startedSaleAt']) {
            $history['onsaleDays'] = $this->getDaysBeforeSale($entity);
        }

        return $history;
    }

    /**
     * Get price related to market
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getPriceToMarket(array $entity): ?int
    {
        return ($entity['sellingPrice'] && $entity['similarCarsAdjustedPrice']) ? (int) round($entity['sellingPrice'] / $entity['similarCarsAdjustedPrice'] * 100) : null;
    }

    /**
     * Get price related to market
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getPriceAverageToMarket(array $entity): ?int
    {
        return ($entity['sellingPrice'] && $entity['similarCarsMedianPrice']) ? (int) round($entity['sellingPrice'] / $entity['similarCarsMedianPrice'] * 100) : null;
    }

    /**
     * Get price related to appraisal
     *
     * @var array $entity
     * @var mixed $appraisal
     *
     * @return int|null
     */
    public function getPriceToAppraisal(array $entity, mixed $appraisal): ?int
    {
        return ($entity['sellingPrice'] && $appraisal) ? ($appraisal['expectedSellingPrice'] ? (int) round($entity['sellingPrice'] / $appraisal['expectedSellingPrice'] * 100) : null) : null;
    }

    /**
     * Get price related to CME
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getPriceToCME(array $entity): ?int
    {
        return ($entity['sellingPrice'] && $entity['recommendedRetailPrice']) ? (int) round($entity['sellingPrice'] / $entity['recommendedRetailPrice'] * 100) : null;
    }

    /**
     * Get previous price
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getPreviousPrice(array $entity): ?int
    {
        return ($entity['sellingPrice'] && $entity['lastSellingPriceChange']) ? (int) ($entity['sellingPrice'] - $entity['lastSellingPriceChange']) : null;
    }

    /**
     * Get buyout price to market
     *
     * @var array $entity
     * @var mixed $appraisal
     *
     * @return int|null
     */
    public function getBuyoutPriceToMarket(array $entity, mixed $appraisal): ?int
    {
        return ($entity['buyoutPrice'] && $appraisal) ? ($appraisal['averagePrice'] ? (int) round($entity['buyoutPrice'] / $appraisal['averagePrice'] * 100) : null) : null;
    }

    /**
     * Get buyout price to CME
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getBuyoutPriceToCME(array $entity): ?int
    {
        return ($entity['buyoutPrice'] && $entity['recommendedRetailPrice']) ? (int) round($entity['buyoutPrice'] / $entity['recommendedRetailPrice'] * 100) : null;
    }

    /**
     * Get appraiser price
     *
     * @var array $entity
     * @var mixed $appraisal
     *
     * @return int|null
     */
    public function getAppraiserPrice(array $entity, mixed $appraisal): ?int
    {
        return $appraisal ? ($appraisal['expectedSellingPrice'] ?? null) : null;
    }

    /**
     * Get real cost
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getRealCost(array $entity): ?int
    {
        return $entity['repairCost'] + ($entity['costUpsale'] ?? 0) + ($entity['costServices'] ?? 0);
    }

    /**
     * Get GM1
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getGM1(array $entity): ?int
    {
        return ($entity['buyoutPrice'] && $entity['sellingPrice']) ? $entity['sellingPrice'] - $entity['buyoutPrice'] : null;
    }

    /**
     * Get G1 %
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getGM1Percent(array $entity): ?int
    {
        return ($entity['buyoutPrice'] && $entity['sellingPrice']) ? (int) round(($entity['sellingPrice'] - $entity['buyoutPrice']) / $entity['sellingPrice'] * 100) : null;
    }

    /**
     * Get planned G1
     *
     * @var array $entity
     * @var mixed $appraisal
     *
     * @return int|null
     */
    public function getGM1Planned(array $entity, mixed $appraisal): ?int
    {
        return ($entity['buyoutPrice'] && $appraisal) ? $appraisal['sellingPrice'] - $entity['buyoutPrice'] : null;
    }

    /**
     * Get planned GM1 %
     *
     * @var array $entity
     * @var mixed $appraisal
     *
     * @return int|null
     */
    public function getGM1PercentPlanned(array $entity, mixed $appraisal): ?int
    {
        return ($appraisal && $entity['sellingPrice']) ? (is_finite((int) round(($appraisal['sellingPrice'] - $appraisal['buyoutPrice']) / $entity['sellingPrice'] * 100)) ? (int) round(($appraisal['sellingPrice'] - $appraisal['buyoutPrice']) / $entity['sellingPrice'] * 100) : null) : null;
    }

    /**
     * Get days on sale
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getDaysOnSale(array $entity): ?int
    {
        return $entity['startedSaleAt'] ? (int) floor((now()->timestamp - strtotime($entity['startedSaleAt'])) / (3600 * 24)) : null;
    }

    /**
     * Get days out of sale
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getDaysOutOfSale(array $entity): ?int
    {
        return $entity['startedSaleAt'] ? $entity['inStockPeriod'] - (int) floor((now()->timestamp - strtotime($entity['startedSaleAt'])) / (3600 * 24)) : null;
    }

    /**
     * Get days before sale
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getDaysBeforeSale(array $entity): ?int
    {
        return $entity['startedSaleAt'] ? (int) floor((strtotime($entity['startedSaleAt']) - strtotime($entity['arrivedAt'])) / (3600 * 24)) : $entity['inStockPeriod'];
    }

    /**
     * Get total discount
     *
     * @var array $entity
     *
     * @return int|null
     */
    public function getDiscountTotal(array $entity): ?int
    {
        return ($entity['discountTradeIn'] ?? 0) + ($entity['discountCredit'] ?? 0) + ($entity['discountInsurance'] ?? 0);
    }

    /**
     * Get appraiser
     *
     * @var array|null $appraisal
     *
     * @return string|null
     */
    public function getAppraiser(array|null $appraisal): ?string
    {
        return $appraisal ? (isset($appraisal['appraiser']) ? ($appraisal['appraiser']['id'] . ': ' . $appraisal['appraiser']['name']) : null) : null;
    }

    /**
     * Get appraisal manager
     *
     * @var array $appraisal
     *
     * @return string|null
     */
    public function getAppraisalManager(array|null $appraisal): ?string
    {
        return $appraisal ? ($appraisal['salesManager']['id'] . ': ' . $appraisal['salesManager']['name']) : null;
    }

    /**
     * Get seller
     *
     * @var array $entity
     *
     * @return string|null
     */
    public function getSeller(array $entity): ?string
    {
        return $entity['soldBy'] ? ($entity['soldBy']['id'] . ': ' . $entity['soldBy']['name']) : null;
    }

    /**
     * Get appraisal manager
     *
     * @var array $placements
     *
     * @return int
     */
    public function getCallsCount(array $placements): int
    {
        return (isset($placements['auto.ru']) ? $placements['auto.ru']['callsCount'] : 0) +
            (isset($placements['drom.ru']) ? $placements['drom.ru']['callsCount'] : 0) +
            (isset($placements['avito.ru']) ? $placements['avito.ru']['callsCount'] : 0);
    }

    /**
     * Get previous id
     *
     * @var array $entity
     *
     * @return string|null
     */
    public function getPreviousId(array $entity): ?string
    {
        $number = Number::where([
            ['VIN', $entity['vin']],
            ['Код объявления', '!=', $entity['id']]
        ])->orderByDesc('id')->first();

        return $number?->{'Код объявления'};
    }

    /**
     * Get auto additional data
     *
     * @var array $entity
     *
     * @return array
     */
    public function getAdditionalData(array $entity): array
    {
        return [
            $this->getOperationsByTypes($entity),
            $this->getAppraisal($entity),
            $this->getPlacements($entity),
            $this->getHistory($entity)
        ];
    }
}
