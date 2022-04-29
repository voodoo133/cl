<?php

/**
 * @charset UTF-8
 *
 * Задание 3
 * В данный момент компания X работает с двумя перевозчиками
 * 1. Почта России
 * 2. DHL
 * У каждого перевозчика своя формула расчета стоимости доставки посылки
 * Почта России до 10 кг берет 100 руб, все что cвыше 10 кг берет 1000 руб
 * DHL за каждый 1 кг берет 100 руб
 * Задача:
 * Необходимо описать архитектуру на php из методов или классов для работы с
 * перевозчиками на предмет получения стоимости доставки по каждому из указанных
 * перевозчиков, согласно данным формулам.
 * При разработке нужно учесть, что количество перевозчиков со временем может
 * возрасти. И делать расчет для новых перевозчиков будут уже другие программисты.
 * Поэтому необходимо построить архитектуру так, чтобы максимально минимизировать
 * ошибки программиста, который будет в дальнейшем делать расчет для нового
 * перевозчика, а также того, кто будет пользоваться данным архитектурным решением.
 *
 */

# Использовать данные:
# любые

abstract class Carrier
{
    public string $name = '';
    protected CarrierCostCalculator $costCalculator;

    public function getCost(int $weight): int
    {
        return $this->costCalculator->calc($weight);
    }
}

abstract class CarrierCostCalculator 
{
    abstract public function calc(int $weight): int;
}

class CarrierCostCalculatorByCondition extends CarrierCostCalculator
{
    private closure $conditionFunc;

    public function __construct(closure $conditionFunc)
    {
        $this->conditionFunc = $conditionFunc;
    }

    public function calc(int $weight): int 
    {
        $conditionFunc = $this->conditionFunc;

        return $conditionFunc($weight);
    }
}

class CarrierCostCalculatorByWeight extends CarrierCostCalculator
{
    private ?int $priceByWeight = null;

    public function __construct(int $priceByWeight) 
    {
        $this->priceByWeight = $priceByWeight;
    }

    public function calc(int $weight): int 
    {
        return $this->priceByWeight * $weight;
    }
}

class RussianPost extends Carrier
{
    public string $name = 'Почта России';

    public function __construct()
    {
        $this->costCalculator = new CarrierCostCalculatorByCondition(function ($weight) {
            return $weight < 10 ? 100 : 1000;
        });
    }
}

class DHL extends Carrier
{
    public string $name = 'DHL';

    public function __construct()
    {
        $this->costCalculator = new CarrierCostCalculatorByWeight(1);
    }
}

$carriers = [ new RussianPost(), new DHL() ];
$weight = 5;

foreach ($carriers as $carrier) {
    echo $carrier->name . ' - ' . $carrier->getCost($weight) . PHP_EOL;
}
