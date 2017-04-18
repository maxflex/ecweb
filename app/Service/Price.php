<?php

namespace App\Service;
use Illuminate\Support\Facades\Redis;
use Cache;

/**
 * Ценник
 */
class Price
{
    /**
     * $part    – [1..4] – какую часть взять
     * $chunk   – кол-во элементов в одной части
     * $step    – порог, после которого цена начинает снижаться
     * $price   – начальная цена
     * $price_step  – шаг уменьшения цены
     */
    public static function parse($part, $chunk, $step = 32, $price = 1700, $price_step = 100)
    {
        // генерируем ценники
        $prices = [];
        foreach(range(1, 4) as $i) {
            foreach(range(1, $step) as $j) {
                $prices[] = $price;
            }
            $price -= $price_step;
        }
        // вырезаем из ценников нужную часть
        $sum = array_slice($prices, ($part - 1) * $chunk, $chunk);

        // суммируем
        return collect($sum)->sum();
        // return number_format(collect($sum)->sum(), 0, '', ' ');
    }
}
