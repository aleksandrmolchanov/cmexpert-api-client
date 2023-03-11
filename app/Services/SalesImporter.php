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
use App\References\ReferenceOutReason;
use App\References\ReferencePts;
use App\References\ReferenceSaleChannel;
use App\References\ReferenceSaleStatus;
use App\References\ReferenceVehicleState;
use App\References\ReferenceVehicleType;
use App\References\ReferenceWheel;

class SalesImporter extends AutoImporter
{
    function __construct() {
        parent::__construct();

        $this->requestUrl = config('api.server.urls.stock');
        $this->requestFilters = [
            'filter[stockState]' => 'out',
            'filter[saleAt][gte]' => $this->getDateAgoAsString()
        ];
        $this->tableName = 'stock_sales';
    }

    /**
     * Get date two month ago as a string
     *
     * @return string
     */
    public function getDateAgoAsString(): string
    {
        return date("Y-m-01\T00:00:00+00:00", strtotime("-1 month"));
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
        $sales = [];

        foreach($data as $entity)
        {
            $sale = [];
            [$operations, $appraisal, $placements, $history] = $this->getAdditionalData($entity);

            $sale['Код объявления'] = $entity['id'];

            $sale['VIN'] = $entity['vin'];

            $sale['Учетный №'] = $entity['dmsCarId'];

            $sale['Гос_номер'] = $entity['registrationPlateNumber'];

            $sale['№ Кузова'] = $entity['bodyNumber'];

            $sale['Парковочное место'] = $entity['parkingSpot'];

            $sale['Идентификатор точки продаж'] =  $entity['dealer']['id'];

            $sale['Точка продаж'] = "{$entity['dealer']['id']}: {$entity['dealer']['id']}";

            $sale['Марка'] = $entity['brand'];

            $sale['Модель'] = $entity['model'];

            $sale['Поколение'] = $entity['generation'];

            $sale['Кузов'] = app(ReferenceBody::class)->get($entity['body']);

            $sale['Модификация'] = ValuesDecorator::getModificationString($entity);

            $sale['Комплектация'] = $entity['searchingEquipmentName'];

            $sale['КПП'] = app(ReferenceGear::class)->getFull($entity['gear']);

            $sale['Привод'] = app(ReferenceDrive::class)->get($entity['drive']);

            $sale['Двигатель'] = app(ReferenceEngine::class)->get($entity['engine']);

            $sale['Объем'] = $entity['volume'];

            $sale['Мощность'] = $entity['power'];

            $sale['Цвет'] = app(ReferenceColor::class)->get($entity['color']);

            $sale['Год'] = $entity['year'];

            $sale['Пробег'] = $entity['mileage'];

            $sale['Руль'] = app(ReferenceWheel::class)->get($entity['wheel']);

            $sale['ПТС'] = app(ReferencePts::class)->get($entity['originalPts']);

            $sale['Владельцев по ПТС'] = $entity['ownersAmount'];

            $sale['Мест в ПТС'] = null;

            $sale['Фотографии'] = $entity['photosAmount'];

            $sale['Видео'] = $entity['videoUrl'] ? 'Да' : 'Нет';

            $sale['Панорама экстерьера'] = $entity['autoRuExteriorPanoramaUrl'] ? 'Да' : 'Нет';

            $sale['Панорама интерьера'] = $entity['autoRuInteriorPanoramaUrl'] ? 'Да' : 'Нет';

            $sale['Статус продажи'] = app(ReferenceSaleStatus::class)->get($entity['saleStatus']);

            $sale['Дата завершения'] = ValuesDecorator::formatDate($entity['saleAt']);

            $sale['Источник продажи'] = app(ReferenceSaleChannel::class)->get($entity['saleChannel']);

            $sale['Продавец'] = $this->getSeller($entity);

            $sale['Номер телефона покупателя'] = $entity['customerPhoneNumber'];

            $sale['Причина'] = app(ReferenceOutReason::class)->get($entity['outReason']);

            $sale['Стратегия продаж'] = $entity['salesTactic'] ? $entity['salesTactic']['name'] : null;

            $sale['Рекомендация стратегии от'] = null;

            $sale['Рекомендация стратегии до'] = null;

            $sale['Цена, ₽'] = $entity['sellingPrice'];

            $sale['Цена, №'] = $history['priceCount'];

            $sale['НДС'] = $entity['isAbleToSellWithVat'] ? 'Возможно' : 'Нет';

            $sale['V-рейтинг'] = $entity['marketPricesRating'];

            $sale['Количество конкурентов'] = $entity['similarCarsTotal'];

            $sale['Цена в рынке, ₽'] = $entity['similarCarsAdjustedPrice'];

            $sale['Цена к рынку, %'] = $this->getPriceToMarket($entity);

            $sale['Цена к средней на рынке, %'] = $this->getPriceAverageToMarket($entity);

            $sale['ПЦП оценщика, ₽'] = $this->getAppraiserPrice($entity, $appraisal);

            $sale['Цена к ПЦП оценщика, %'] = $this->getPriceToAppraisal($entity, $appraisal);

            $sale['ПЦП CM, ₽'] = $entity['recommendedRetailPrice'];

            $sale['Цена к ПЦП CM, %'] = $this->getPriceToCME($entity);

            $sale['Первая цена, ₽'] = $history['priceFirst'];

            $sale['Предыдущая цена, ₽'] = $this->getPreviousPrice($entity);

            $sale['Переоценка, ₽'] = $entity['lastSellingPriceChange'];

            $sale['Дата последнего изменения цены'] = $entity['lastPriceChangedAt'] ? ValuesDecorator::formatDate($entity['lastPriceChangedAt']) : null;

            $sale['Дней от переоценки в продаже'] = $entity['lastPriceChangedDays'];

            $sale['Количество переоценок'] = $history['priceCount'];

            $sale['Дней на складе'] = $entity['inStockPeriod'];

            $sale['Дней в продаже'] = $this->getDaysOnSale($entity);

            $sale['Дней не в продаже'] = $this->getDaysOutOfSale($entity);

            $sale['Дней в продаже на других ТП'] = $history['otherDealersDays'];

            $sale['Дней до вывода в продажу'] = $this->getDaysBeforeSale($entity);

            $sale['Дней в предпродажной подготовке'] = $operations['prepare']['days'];

            $sale['Дней в ремонте'] = $operations['repair']['days'];

            $sale['Дней в логистике'] = $operations['logistics']['days'];

            $sale['Дней решения юридических проблем'] = $operations['legalproblems']['days'];

            $sale['Дней решения прочих проблем'] = null;

            $sale['Дней подготовки контента'] = null;

            $sale['Дней в резерве'] = $operations['reserve']['days'];

            $sale['Оценка состояния'] = app(ReferenceVehicleState::class)->get($entity['vehicleState']);

            $sale['Ликвидность'] = $entity['complexEstimate'];

            $sale['Категория ТС'] = app(ReferenceVehicleType::class)->get($entity['vehicleType']);

            $sale['Тип контракта'] = app(ReferenceContractType::class)->get($entity['contractType']);

            $sale['Источник'] = $entity['inChannel'] ? app(ReferenceInChannel::class)->get($entity['inChannel']) : null;

            $sale['Марка (Trade-in)'] = null;

            $sale['Модель (Trade-in)'] = null;

            $sale['VIN (Trade-in)'] = null;

            $sale['Дата переезда'] = $history['changeDealerAt'];

            $sale['Предыдущая ТП'] = $history['lastDealer'];

            $sale['№ предыдущего объявления'] = $this->getPreviousId($entity);

            $sale['Оценщик'] = $this->getAppraiser($appraisal);

            $sale['Менеджер закупа'] = $this->getAppraisalManager($appraisal);

            $sale['Стоимость закупки, ₽'] = $entity['buyoutPrice'];

            $sale['Стоимость приобретения к рынку на день сделки'] = $this->getBuyoutPriceToMarket($entity, $appraisal);

            $sale['Стоимость приобретения к ПЦП Cm на день сделки'] = $this->getBuyoutPriceToCME($entity);

            $sale['Планируемые затраты на ПП, ₽'] = null;

            $sale['Фактические затраты на ПП, ₽'] = $this->getRealCost($entity);

            $sale['Цена продажи, ₽'] = $entity['sellingPrice'];

            $sale['Скидка на trade-in, ₽'] = $entity['discountTradeIn'];

            $sale['Скидка на кредит, ₽'] = $entity['discountCredit'];

            $sale['Скидка на страховку, ₽'] = $entity['discountInsurance'];

            $sale['Максимальная сумма скидки, ₽'] = $entity['discountMax'];

            $sale['Общая скидка, ₽'] = $this->getDiscountTotal($entity);

            $sale['Прибыль от кредита'] = $entity['revenueCredit'];

            $sale['Прибыль от страховки'] = $entity['revenueInsurance'];

            $sale['Прибыль от ДО и аксессуаров'] = $entity['revenueUpsale'];

            $sale['Прибыль от продажи услуг и сервисов'] = $entity['revenueServices'];

            $sale['Прибыль от продажи прочих кредитных и страховых продуктов'] = $entity['revenueOtherFinanceInsurance'];

            $sale['GM1'] = $this->getGM1($entity);

            $sale['GM1, %'] = $this->getGM1Percent($entity);

            $sale['Планируемая GM1'] = $this->getGM1Planned($entity, $appraisal);

            $sale['Планируемая GM1, %'] = $this->getGM1PercentPlanned($entity, $appraisal);

            $sale['GM2'] = $entity['markup'];

            $sale['GM2, %'] = $entity['markupPercent'];

            $sale['Дата последней регистрации в ГИБДД'] = null;

            $sale['Дней с последней регистрации в ГИБДД'] = null;

            $sale['Дата регистрации после продажи в ГИБДД'] = null;

            $sale['Дней от продажи до регистрации после продажи в ГИБДД'] = null;

            $sale['Затраты на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['publicationExpenses'] : null;

            $sale['Затраты на размещение на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['placementsExpenses'] : null;

            $sale['Затраты на услуги продвижения на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['promotionsExpenses'] : null;

            $sale['Затраты на звонки с auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['callsExpenses'] : null;

            $sale['Просмотров объявления на auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['viewsCount'] : null;

            $sale['Просмотров контактов на auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['contactViewsCount'] : null;

            $sale['Добавлений в избранное на auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['starredCount'] : null;

            $sale['Звонков с auto_ru'] = isset($placements['auto.ru']) ? $placements['auto.ru']['callsCount'] : null;

            $sale['Стоимость просмотра на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['viewCost'] : null;

            $sale['Стоимость просмотра контакта на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['contactViewCost'] : null;

            $sale['Стоимость добавления в избранное на auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['starringCost'] : null;

            $sale['Стоимость привлечения звонка с auto_ru, ₽'] = isset($placements['auto.ru']) ? $placements['auto.ru']['callCost'] : null;

            $sale['Затраты на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['publicationExpenses'] : null;

            $sale['Затраты на размещение на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['placementsExpenses'] : null;

            $sale['Затраты на услуги продвижения на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['promotionsExpenses'] : null;

            $sale['Затраты на звонки с drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['callsExpenses'] : null;

            $sale['Просмотров объявления на drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['viewsCount'] : null;

            $sale['Просмотров контактов на drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['contactViewsCount'] : null;

            $sale['Добавлений в избранное на drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['starredCount'] : null;

            $sale['Звонков с drom_ru'] = isset($placements['drom.ru']) ? $placements['drom.ru']['callsCount'] : null;

            $sale['Стоимость просмотра на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['viewCost'] : null;

            $sale['Стоимость просмотра контакта на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['contactViewCost'] : null;

            $sale['Стоимость добавления в избранное на drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['starringCost'] : null;

            $sale['Стоимость привлечения звонка с drom_ru, ₽'] = isset($placements['drom.ru']) ? $placements['drom.ru']['callCost'] : null;

            $sale['Затраты на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['publicationExpenses'] : null;

            $sale['Затраты на размещение на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['placementsExpenses'] : null;

            $sale['Затраты на услуги продвижения на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['promotionsExpenses'] : null;

            $sale['Затраты на звонки с avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['callsExpenses'] : null;

            $sale['Просмотров объявления на avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['viewsCount'] : null;

            $sale['Просмотров контактов на avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['contactViewsCount'] : null;

            $sale['Добавлений в избранное на avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['starredCount'] : null;

            $sale['Звонков с avito_ru'] = isset($placements['avito.ru']) ? $placements['avito.ru']['callsCount'] : null;

            $sale['Стоимость просмотра на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['viewCost'] : null;

            $sale['Стоимость просмотра контакта на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['contactViewCost'] : null;

            $sale['Стоимость добавления в избранное на avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['starringCost'] : null;

            $sale['Стоимость привлечения звонка с avito_ru, ₽'] = isset($placements['avito.ru']) ? $placements['avito.ru']['callCost'] : null;

            $sale['Всего звонков'] = $this->getCallsCount($placements);

            $sale['Реклама итого, ₽'] = $entity['totalMarketingExpenses'];

            $sale['Появился в продаже'] = $entity['publishStartedAt'] ? ValuesDecorator::formatDate($entity['publishStartedAt']) : null;

            $sales[] = $sale;
        }

        return $sales;
    }
}
