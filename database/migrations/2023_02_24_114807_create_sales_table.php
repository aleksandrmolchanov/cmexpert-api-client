<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('Код объявления');
            $table->string('VIN');
            $table->string('Учетный №');
            $table->string('Гос_номер');
            $table->string('№ Кузова');
            $table->string('Парковочное место');
            $table->integer('Идентификатор точки продаж');
            $table->string('Точка продаж');
            $table->string('Марка');
            $table->string('Модель');
            $table->string('Поколение');
            $table->string('Кузов');
            $table->string('Модификация');
            $table->string('Комплектация');
            $table->string('КПП');
            $table->string('Привод');
            $table->string('Двигатель');
            $table->decimal('Объем', 2, 1);
            $table->integer('Мощность');
            $table->string('Цвет');
            $table->integer('Год');
            $table->integer('Пробег');
            $table->string('Руль');
            $table->string('ПТС');
            $table->integer('Владельцев по ПТС');
            $table->integer('Мест в ПТС');
            $table->integer('Фотографии');
            $table->string('Видео');
            $table->string('Панорама экстерьера');
            $table->string('Панорама интерьера');
            $table->string('Статус продажи');
            $table->string('Дата завершения');
            $table->string('Источник продажи');
            $table->string('Продавец');
            $table->string('Номер телефона покупателя');
            $table->string('Причина');
            $table->string('Стратегия продаж');
            $table->integer('Рекомендация стратегии от');
            $table->integer('Рекомендация стратегии до');
            $table->integer('Цена, ₽');
            $table->integer('Цена, №');
            $table->string('НДС');
            $table->string('V-рейтинг');
            $table->integer('Количество конкурентов');
            $table->integer('Цена в рынке, ₽');
            $table->integer('Цена к рынку, %');
            $table->integer('Цена к средней на рынке, %');
            $table->integer('ПЦП оценщика, ₽');
            $table->integer('Цена к ПЦП оценщика, %');
            $table->integer('ПЦП CM, ₽');
            $table->integer('Цена к ПЦП CM, %');
            $table->integer('Первая цена, ₽');
            $table->integer('Предыдущая цена, ₽');
            $table->integer('Переоценка, ₽');
            $table->string('Дата последнего изменения цены');
            $table->integer('Дней от переоценки в продаже');
            $table->integer('Количество переоценок');
            $table->integer('Дней на складе');
            $table->integer('Дней в продаже');
            $table->integer('Дней не в продаже');
            $table->integer('Дней в продаже на других ТП');
            $table->integer('Дней до вывода в продажу');
            $table->integer('Дней в предпродажной подготовке');
            $table->integer('Дней в ремонте');
            $table->integer('Дней в логистике');
            $table->integer('Дней решения юридических проблем');
            $table->integer('Дней решения прочих проблем');
            $table->integer('Дней подготовки контента');
            $table->integer('Дней в резерве');
            $table->string('Оценка состояния');
            $table->string('Ликвидность');
            $table->string('Категория ТС');
            $table->string('Тип контракта');
            $table->string('Источник');
            $table->string('Марка (Trade-in)');
            $table->string('Модель (Trade-in)');
            $table->string('VIN (Trade-in)');
            $table->string('Дата переезда');
            $table->string('Предыдущая ТП');
            $table->integer('№ предыдущего объявления');
            $table->string('Оценщик');
            $table->string('Менеджер закупа');
            $table->integer('Стоимость закупки, ₽');
            $table->integer('Стоимость приобретения к рынку на день сделки');
            $table->integer('Стоимость приобретения к ПЦП CM на день сделки');
            $table->integer('Планируемые затраты на ПП, ₽');
            $table->integer('Фактические затраты на ПП, ₽');
            $table->integer('Цена продажи, ₽');
            $table->integer('Скидка на trade-in, ₽');
            $table->integer('Скидка на кредит, ₽');
            $table->integer('Скидка на страховку, ₽');
            $table->integer('Максимальная сумма скидки, ₽');
            $table->integer('Общая скидка, ₽');
            $table->integer('Прибыль от кредита');
            $table->integer('Прибыль от страховки');
            $table->integer('Прибыль от ДО и аксессуаров');
            $table->integer('Прибыль от продажи услуг и сервисов');
            $table->integer('Прибыль от продажи прочих кредитных и страховых продуктов');
            $table->integer('GM1');
            $table->integer('GM1, %');
            $table->integer('Планируемая GM1');
            $table->integer('Планируемая GM1, %');
            $table->integer('GM2');
            $table->integer('GM2, %');
            $table->string('Дата последней регистрации в ГИБДД');
            $table->integer('Дней с последней регистрации в ГИБДД');
            $table->string('Дата регистрации после продажи в ГИБДД');
            $table->integer('Дней от продажи до регистрации после продажи в ГИБДД');
            $table->float('Затраты на auto_ru, ₽');
            $table->float('Затраты на размещение на auto_ru, ₽');
            $table->float('Затраты на услуги продвижения на auto_ru, ₽');
            $table->float('Затраты на звонки с auto_ru, ₽');
            $table->integer('Просмотров объявления на auto_ru');
            $table->integer('Просмотров контактов на auto_ru');
            $table->integer('Добавлений в избранное на auto_ru');
            $table->integer('Звонков с auto_ru');
            $table->float('Стоимость просмотра на auto_ru, ₽');
            $table->float('Стоимость просмотра контакта на auto_ru, ₽');
            $table->float('Стоимость добавления в избранное на auto_ru, ₽');
            $table->float('Стоимость привлечения звонка с auto_ru, ₽');
            $table->float('Затраты на drom_ru, ₽');
            $table->float('Затраты на размещение на drom_ru, ₽');
            $table->float('Затраты на услуги продвижения на drom_ru, ₽');
            $table->float('Затраты на звонки с drom_ru, ₽');
            $table->integer('Просмотров объявления на drom_ru');
            $table->integer('Просмотров контактов на drom_ru');
            $table->integer('Добавлений в избранное на drom_ru');
            $table->integer('Звонков с drom_ru');
            $table->float('Стоимость просмотра на drom_ru, ₽');
            $table->float('Стоимость просмотра контакта на drom_ru, ₽');
            $table->float('Стоимость добавления в избранное на drom_ru, ₽');
            $table->float('Стоимость привлечения звонка с drom_ru, ₽');
            $table->float('Затраты на avitoru, ₽');
            $table->float('Затраты на размещение на avito_ru, ₽');
            $table->float('Затраты на услуги продвижения на avito_ru, ₽');
            $table->float('Затраты на звонки с avito_ru, ₽');
            $table->integer('Просмотров объявления на avito_ru');
            $table->integer('Просмотров контактов на avito_ru');
            $table->integer('Добавлений в избранное на avito_ru');
            $table->integer('Звонков с avito_ru');
            $table->float('Стоимость просмотра на avito_ru, ₽');
            $table->float('Стоимость просмотра контакта на avito_ru, ₽');
            $table->float('Стоимость добавления в избранное на avito_ru, ₽');
            $table->float('Стоимость привлечения звонка с avito_ru, ₽');
            $table->integer('Всего звонков');
            $table->float('Реклама итого, ₽');
            $table->string('Появился в продаже');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_sales');
    }
};
