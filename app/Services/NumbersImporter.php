<?php

namespace App\Services;

use App\Classes\ValuesDecorator;
use App\References\ReferenceBody;
use App\References\ReferenceColor;
use App\References\ReferenceContractType;
use App\References\ReferenceDrive;
use App\References\ReferenceEngine;
use App\References\ReferenceGear;
use App\References\ReferenceInChannel;
use App\References\ReferencePts;
use App\References\ReferenceSaleStatus;
use App\References\ReferenceVehicleState;
use App\References\ReferenceVehicleType;
use App\References\ReferenceWheel;

class NumbersImporter extends AutoImporter
{
    function __construct() {
        parent::__construct();

        $this->requestUrl = config('api.server.urls.stock');
        $this->requestFilters = ['filter[stockState]' => 'in'];
        $this->tableName = 'stock_numbers';
    }

    /**
     * Process single page from api
     *
     * @var array $data
     *
     * @return array
     */
    public function processPage(array $data): array
    {
        $numbers = [];

        foreach($data as $entity)
        {
            $number = [];
            [$operations, $appraisal, $placements, $history] = $this->getAdditionalData($entity);

            $number['Код объявления'] = $entity['id'];

            $number['VIN'] = $entity['vin'];

            $number['Учетный №'] = $entity['dmsCarId'];

            $number['Гос_номер'] = $entity['registrationPlateNumber'];

            $number['№ Кузова'] = $entity['bodyNumber'];

            $number['Парковочное место'] = $entity['parkingSpot'];

            $number['Идентификатор точки продаж'] =  $entity['dealer']['id'];

            $number['Точка продаж'] = "{$entity['dealer']['id']}: {$entity['dealer']['id']}";

            $number['Марка'] = $entity['brand'];

            $number['Модель'] = $entity['model'];

            $number['Поколение'] = $entity['generation'];

            $number['Кузов'] = app(ReferenceBody::class)->get($entity['body']);

            $number['Модификация'] = ValuesDecorator::getModificationString($entity);

            $number['Комплектация'] = $entity['searchingEquipmentName'];

            $number['КПП'] = app(ReferenceGear::class)->getFull($entity['gear']);

            $number['Привод'] = app(ReferenceDrive::class)->get($entity['drive']);

            $number['Двигатель'] = app(ReferenceEngine::class)->get($entity['engine']);

            $number['Объем'] = $entity['volume'];

            $number['Мощность'] = $entity['power'];

            $number['Цвет'] = app(ReferenceColor::class)->get($entity['color']);

            $number['Год'] = $entity['year'];

            $number['Пробег'] = $entity['mileage'];

            $number['Руль'] = app(ReferenceWheel::class)->get($entity['wheel']);

            $number['ПТС'] = app(ReferencePts::class)->get($entity['originalPts']);

            $number['Владельцев по ПТС'] = $entity['ownersAmount'];

            $number['Мест в ПТС'] = null;

            $number['Фотографии'] = $entity['photosAmount'];

            $number['Видео'] = $entity['videoUrl'] ? 'Да' : 'Нет';

            $number['Панорама экстерьера'] = $entity['autoRuExteriorPanoramaUrl'] ? 'Да' : 'Нет';

            $number['Панорама интерьера'] = $entity['autoRuInteriorPanoramaUrl'] ? 'Да' : 'Нет';

            $number['auto_ru'] = ValuesDecorator::getPublishedAutoRuString($entity);

            $number['avito_ru'] = ValuesDecorator::getPublishedAvitoRuString($entity);

            $number['drom_ru'] = ValuesDecorator::getPublishedDromRuString($entity);

            $number['Статус продажи'] = app(ReferenceSaleStatus::class)->get($entity['saleStatus']);

            $number['Предпродажная подготовка'] = $operations['prepare']['now'] ? 'Да' : 'Нет';

            $number['Ремонт'] = $operations['repair']['now'] ? 'Да' : 'Нет';

            $number['Логистика'] = $operations['logistics']['now'] ? 'Да' : 'Нет';

            $number['Юридические проблемы'] = $operations['legalproblems']['now'] ? 'Да' : 'Нет';

            $number['Прочие проблемы'] = null;

            $number['Подготовка контента'] = null;

            $number['Резерв'] = $operations['reserve']['now'] ? 'Да' : 'Нет';

            $number['Комментарии по статусам'] = null;

            $number['Стратегия продаж'] = $entity['salesTactic'] ? $entity['salesTactic']['name'] : null;

            $number['Рекомендация стратегии от'] = null;

            $number['Рекомендация стратегии до'] = null;

            $number['Цена, ₽'] = $entity['sellingPrice'];

            $number['Цена, №'] = $history['priceCount'];

            $number['НДС'] = $entity['isAbleToSellWithVat'] ? 'Возможно' : 'Нет';

            $number['V-рейтинг'] = $entity['marketPricesRating'];

            $number['Количество конкурентов'] = $entity['similarCarsTotal'];

            $number['Цена в рынке, ₽'] = $entity['similarCarsAdjustedPrice'];

            $number['Цена к рынку, %'] = $this->getPriceToMarket($entity);

            $number['Цена к средней на рынке, %'] = $this->getPriceAverageToMarket($entity);

            $number['ПЦП оценщика, ₽'] = $this->getAppraiserPrice($entity, $appraisal);

            $number['Цена к ПЦП оценщика, %'] = $this->getPriceToAppraisal($entity, $appraisal);

            $number['ПЦП CM, ₽'] = $entity['recommendedRetailPrice'];

            $number['Цена к ПЦП CM, %'] = $this->getPriceToCME($entity);

            $number['Первая цена, ₽'] = $history['priceFirst'];

            $number['Предыдущая цена, ₽'] = $this->getPreviousPrice($entity);

            $number['Переоценка, ₽'] = $entity['lastSellingPriceChange'];

            $number['Дата последнего изменения цены'] = $entity['lastPriceChangedAt'] ? ValuesDecorator::formatDate($entity['lastPriceChangedAt']) : null;

            $number['Дней от переоценки в продаже'] = $entity['lastPriceChangedDays'];

            $number['Количество переоценок'] = $history['priceCount'];

            $number['Дней на складе'] = $entity['inStockPeriod'];

            $number['Дней в продаже'] = $this->getDaysOnSale($entity);

            $number['Дней не в продаже'] = $this->getDaysOutOfSale($entity);

            $number['Дней в продаже на других ТП'] = $history['otherDealersDays'];

            $number['Дней до вывода в продажу'] = $this->getDaysBeforeSale($entity);

            $number['Дней в предпродажной подготовке'] = $operations['prepare']['days'];

            $number['Дней в ремонте'] = $operations['repair']['days'];

            $number['Дней в логистике'] = $operations['logistics']['days'];

            $number['Дней решения юридических проблем'] = $operations['legalproblems']['days'];

            $number['Дней решения прочих проблем'] = null;

            $number['Дней подготовки контента'] = null;

            $number['Дней в резерве'] = $operations['reserve']['days'];

            $number['Оценка состояния'] = app(ReferenceVehicleState::class)->get($entity['vehicleState']);

            $number['Ликвидность'] = $entity['complexEstimate'];

            $number['Категория ТС'] = app(ReferenceVehicleType::class)->get($entity['vehicleType']);

            $number['Тип контракта'] = app(ReferenceContractType::class)->get($entity['contractType']);

            $number['Источник'] = $entity['inChannel'] ? app(ReferenceInChannel::class)->get($entity['inChannel']) : null;

            $number['Марка (Trade-in)'] = null;

            $number['Модель (Trade-in)'] = null;

            $number['VIN (Trade-in)'] = null;

            $number['Дата переезда'] = $history['changeDealerAt'];

            $number['Предыдущая ТП'] = $history['lastDealer'];

            $number['№ предыдущего объявления'] = $this->getPreviousId($entity);

            $number['Оценщик'] = $this->getAppraiser($appraisal);

            $number['Менеджер закупа'] = $this->getAppraisalManager($appraisal);

            $number['Стоимость закупки, ₽'] = $entity['buyoutPrice'];

            $number['Стоимость приобретения к рынку на день сделки'] = $this->getBuyoutPriceToMarket($entity, $appraisal);

            $number['Стоимость приобретения к ПЦП Cm на день сделки'] = $this->getBuyoutPriceToCME($entity);

            $number['Планируемые затраты на ПП, ₽'] = null;

            $number['Фактические затраты на ПП, ₽'] = $this->getRealCost($entity);

            $number['GM1'] = $this->getGM1($entity);

            $number['GM1, %'] = $this->getGM1Percent($entity);

            $number['Планируемая GM1'] = $this->getGM1Planned($entity, $appraisal);

            $number['Планируемая GM1, %'] = $this->getGM1PercentPlanned($entity, $appraisal);

            $number['GM2'] = $entity['markup'];

            $number['GM2, %'] = $entity['markupPercent'];

            $number['Дата последней регистрации в ГИБДД'] = null;

            $number['Дней с последней регистрации в ГИБДД'] = null;

            $number['Затраты на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['publicationExpenses'] : null;

            $number['Затраты на размещение на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['placementsExpenses'] : null;

            $number['Затраты на услуги продвижения на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['promotionsExpenses'] : null;

            $number['Затраты на звонки с auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['callsExpenses'] : null;

            $number['Просмотров объявления на auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['viewsCount'] : null;

            $number['Просмотров контактов на auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['contactViewsCount'] : null;

            $number['Добавлений в избранное на auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['starredCount'] : null;

            $number['Звонков с auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['callsCount'] : null;

            $number['Стоимость просмотра на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['viewCost'] : null;

            $number['Стоимость просмотра контакта на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['contactViewCost'] : null;

            $number['Стоимость добавления в избранное на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['starringCost'] : null;

            $number['Стоимость привлечения звонка с auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['callCost'] : null;

            $number['Затраты на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['publicationExpenses'] : null;

            $number['Затраты на размещение на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['placementsExpenses'] : null;

            $number['Затраты на услуги продвижения на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['promotionsExpenses'] : null;

            $number['Затраты на звонки с drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['callsExpenses'] : null;

            $number['Просмотров объявления на drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['viewsCount'] : null;

            $number['Просмотров контактов на drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['contactViewsCount'] : null;

            $number['Добавлений в избранное на drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['starredCount'] : null;

            $number['Звонков с drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['callsCount'] : null;

            $number['Стоимость просмотра на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['viewCost'] : null;

            $number['Стоимость просмотра контакта на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['contactViewCost'] : null;

            $number['Стоимость добавления в избранное на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['starringCost'] : null;

            $number['Стоимость привлечения звонка с drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['callCost'] : null;

            $number['Затраты на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['publicationExpenses'] : null;

            $number['Затраты на размещение на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['placementsExpenses'] : null;

            $number['Затраты на услуги продвижения на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['promotionsExpenses'] : null;

            $number['Затраты на звонки с avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['callsExpenses'] : null;

            $number['Просмотров объявления на avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['viewsCount'] : null;

            $number['Просмотров контактов на avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['contactViewsCount'] : null;

            $number['Добавлений в избранное на avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['starredCount'] : null;

            $number['Звонков с avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['callsCount'] : null;

            $number['Стоимость просмотра на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['viewCost'] : null;

            $number['Стоимость просмотра контакта на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['contactViewCost'] : null;

            $number['Стоимость добавления в избранное на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['starringCost'] : null;

            $number['Стоимость привлечения звонка с avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['callCost'] : null;

            $number['Всего звонков'] = $this->getCallsCount($placements);

            $number['Реклама итого, ₽'] = $entity['totalMarketingExpenses'];

            $number['Дата остатков'] = ValuesDecorator::formatDate(now());

            $number['Дата поступления на склад'] = ValuesDecorator::formatDate($entity['arrivedAt']);

            $numbers[] = $number;
        }

        return $numbers;
    }

    /**
     * Get pages count
     *
     * @return int
     */
    public function getPagesCount(): int
    {
        $pageResponse = $this->getData($this->requestUrl, array_merge(['page' => 1, 'perPage' => config('api.request.perPage')], $this->requestFilters));
        return $pageResponse->successful() ? $pageResponse->header('x-pagination-page-count') : 0;
    }
}
