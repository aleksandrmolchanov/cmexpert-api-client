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
        Schema::table('stock_sales', function($table) {
            $table->string('VIN')->nullable()->change();
            $table->string('Гос_номер')->nullable()->change();
            $table->string('№ Кузова')->nullable()->change();
            $table->string('Парковочное место')->nullable()->change();
            $table->integer('Идентификатор точки продаж')->nullable()->change();
            $table->string('Точка продаж')->nullable()->change();
            $table->string('Марка')->nullable()->change();
            $table->string('Модель')->nullable()->change();
            $table->string('Поколение')->nullable()->change();
            $table->string('Кузов')->nullable()->change();
            $table->string('Модификация')->nullable()->change();
            $table->string('Комплектация')->nullable()->change();
            $table->string('КПП')->nullable()->change();
            $table->string('Привод')->nullable()->change();
            $table->string('Двигатель')->nullable()->change();
            $table->decimal('Объем', 2, 1)->nullable()->change();
            $table->integer('Мощность')->nullable()->change();
            $table->string('Цвет')->nullable()->change();
            $table->integer('Год')->nullable()->change();
            $table->integer('Пробег')->nullable()->change();
            $table->string('Руль')->nullable()->change();
            $table->string('ПТС')->nullable()->change();
            $table->integer('Владельцев по ПТС')->nullable()->change();
            $table->integer('Мест в ПТС')->nullable()->change();
            $table->integer('Фотографии')->nullable()->change();
            $table->string('Видео')->nullable()->change();
            $table->string('Панорама экстерьера')->nullable()->change();
            $table->string('Панорама интерьера')->nullable()->change();
            $table->string('Статус продажи')->nullable()->change();
            $table->string('Дата завершения')->nullable()->change();
            $table->string('Источник продажи')->nullable()->change();
            $table->string('Продавец')->nullable()->change();
            $table->string('Номер телефона покупателя')->nullable()->change();
            $table->string('Причина')->nullable()->change();
            $table->string('Стратегия продаж')->nullable()->change();
            $table->integer('Рекомендация стратегии от')->nullable()->change();
            $table->integer('Рекомендация стратегии до')->nullable()->change();
            $table->integer('Цена, ₽')->nullable()->change();
            $table->integer('Цена, №')->nullable()->change();
            $table->string('НДС')->nullable()->change();
            $table->string('V-рейтинг')->nullable()->change();
            $table->integer('Количество конкурентов')->nullable()->change();
            $table->integer('Цена в рынке, ₽')->nullable()->change();
            $table->integer('Цена к рынку, %')->nullable()->change();
            $table->integer('Цена к средней на рынке, %')->nullable()->change();
            $table->integer('ПЦП оценщика, ₽')->nullable()->change();
            $table->integer('Цена к ПЦП оценщика, %')->nullable()->change();
            $table->integer('ПЦП CM, ₽')->nullable()->change();
            $table->integer('Цена к ПЦП CM, %')->nullable()->change();
            $table->integer('Первая цена, ₽')->nullable()->change();
            $table->integer('Предыдущая цена, ₽')->nullable()->change();
            $table->integer('Переоценка, ₽')->nullable()->change();
            $table->string('Дата последнего изменения цены')->nullable()->change();
            $table->integer('Дней от переоценки в продаже')->nullable()->change();
            $table->integer('Количество переоценок')->nullable()->change();
            $table->integer('Дней на складе')->nullable()->change();
            $table->integer('Дней в продаже')->nullable()->change();
            $table->integer('Дней не в продаже')->nullable()->change();
            $table->integer('Дней в продаже на других ТП')->nullable()->change();
            $table->integer('Дней до вывода в продажу')->nullable()->change();
            $table->integer('Дней в предпродажной подготовке')->nullable()->change();
            $table->integer('Дней в ремонте')->nullable()->change();
            $table->integer('Дней в логистике')->nullable()->change();
            $table->integer('Дней решения юридических проблем')->nullable()->change();
            $table->integer('Дней решения прочих проблем')->nullable()->change();
            $table->integer('Дней подготовки контента')->nullable()->change();
            $table->integer('Дней в резерве')->nullable()->change();
            $table->string('Оценка состояния')->nullable()->change();
            $table->string('Ликвидность')->nullable()->change();
            $table->string('Категория ТС')->nullable()->change();
            $table->string('Тип контракта')->nullable()->change();
            $table->string('Источник')->nullable()->change();
            $table->string('Марка (Trade-in)')->nullable()->change();
            $table->string('Модель (Trade-in)')->nullable()->change();
            $table->string('VIN (Trade-in)')->nullable()->change();
            $table->string('Дата переезда')->nullable()->change();
            $table->string('Предыдущая ТП')->nullable()->change();
            $table->integer('№ предыдущего объявления')->nullable()->change();
            $table->string('Оценщик')->nullable()->change();
            $table->string('Менеджер закупа')->nullable()->change();
            $table->integer('Стоимость закупки, ₽')->nullable()->change();
            $table->integer('Стоимость приобретения к рынку на день сделки')->nullable()->change();
            $table->integer('Стоимость приобретения к ПЦП CM на день сделки')->nullable()->change();
            $table->integer('Планируемые затраты на ПП, ₽')->nullable()->change();
            $table->integer('Фактические затраты на ПП, ₽')->nullable()->change();
            $table->integer('Цена продажи, ₽')->nullable()->change();
            $table->integer('Скидка на trade-in, ₽')->nullable()->change();
            $table->integer('Скидка на кредит, ₽')->nullable()->change();
            $table->integer('Скидка на страховку, ₽')->nullable()->change();
            $table->integer('Максимальная сумма скидки, ₽')->nullable()->change();
            $table->integer('Общая скидка, ₽')->nullable()->change();
            $table->integer('Прибыль от кредита')->nullable()->change();
            $table->integer('Прибыль от страховки')->nullable()->change();
            $table->integer('Прибыль от ДО и аксессуаров')->nullable()->change();
            $table->integer('Прибыль от продажи услуг и сервисов')->nullable()->change();
            $table->integer('Прибыль от продажи прочих кредитных и страховых продуктов')->nullable()->change();
            $table->integer('GM1')->nullable()->change();
            $table->integer('GM1, %')->nullable()->change();
            $table->integer('Планируемая GM1')->nullable()->change();
            $table->integer('Планируемая GM1, %')->nullable()->change();
            $table->integer('GM2')->nullable()->change();
            $table->integer('GM2, %')->nullable()->change();
            $table->string('Дата последней регистрации в ГИБДД')->nullable()->change();
            $table->integer('Дней с последней регистрации в ГИБДД')->nullable()->change();
            $table->string('Дата регистрации после продажи в ГИБДД')->nullable()->change();
            $table->integer('Дней от продажи до регистрации после продажи в ГИБДД')->nullable()->change();
            $table->float('Затраты на auto_ru, ₽')->nullable()->change();
            $table->float('Затраты на размещение на auto_ru, ₽')->nullable()->change();
            $table->float('Затраты на услуги продвижения на auto_ru, ₽')->nullable()->change();
            $table->float('Затраты на звонки с auto_ru, ₽')->nullable()->change();
            $table->integer('Просмотров объявления на auto_ru')->nullable()->change();
            $table->integer('Просмотров контактов на auto_ru')->nullable()->change();
            $table->integer('Добавлений в избранное на auto_ru')->nullable()->change();
            $table->integer('Звонков с auto_ru')->nullable()->change();
            $table->float('Стоимость просмотра на auto_ru, ₽')->nullable()->change();
            $table->float('Стоимость просмотра контакта на auto_ru, ₽')->nullable()->change();
            $table->float('Стоимость добавления в избранное на auto_ru, ₽')->nullable()->change();
            $table->float('Стоимость привлечения звонка с auto_ru, ₽')->nullable()->change();
            $table->float('Затраты на drom_ru, ₽')->nullable()->change();
            $table->float('Затраты на размещение на drom_ru, ₽')->nullable()->change();
            $table->float('Затраты на услуги продвижения на drom_ru, ₽')->nullable()->change();
            $table->float('Затраты на звонки с drom_ru, ₽')->nullable()->change();
            $table->integer('Просмотров объявления на drom_ru')->nullable()->change();
            $table->integer('Просмотров контактов на drom_ru')->nullable()->change();
            $table->integer('Добавлений в избранное на drom_ru')->nullable()->change();
            $table->integer('Звонков с drom_ru')->nullable()->change();
            $table->float('Стоимость просмотра на drom_ru, ₽')->nullable()->change();
            $table->float('Стоимость просмотра контакта на drom_ru, ₽')->nullable()->change();
            $table->float('Стоимость добавления в избранное на drom_ru, ₽')->nullable()->change();
            $table->float('Стоимость привлечения звонка с drom_ru, ₽')->nullable()->change();
            $table->float('Затраты на avitoru, ₽')->nullable()->change();
            $table->float('Затраты на размещение на avito_ru, ₽')->nullable()->change();
            $table->float('Затраты на услуги продвижения на avito_ru, ₽')->nullable()->change();
            $table->float('Затраты на звонки с avito_ru, ₽')->nullable()->change();
            $table->integer('Просмотров объявления на avito_ru')->nullable()->change();
            $table->integer('Просмотров контактов на avito_ru')->nullable()->change();
            $table->integer('Добавлений в избранное на avito_ru')->nullable()->change();
            $table->integer('Звонков с avito_ru')->nullable()->change();
            $table->float('Стоимость просмотра на avito_ru, ₽')->nullable()->change();
            $table->float('Стоимость просмотра контакта на avito_ru, ₽')->nullable()->change();
            $table->float('Стоимость добавления в избранное на avito_ru, ₽')->nullable()->change();
            $table->float('Стоимость привлечения звонка с avito_ru, ₽')->nullable()->change();
            $table->integer('Всего звонков')->nullable()->change();
            $table->float('Реклама итого, ₽')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
