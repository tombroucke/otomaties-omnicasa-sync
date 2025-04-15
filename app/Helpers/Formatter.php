<?php

namespace Otomaties\Omnicasa\Helpers;

class Formatter
{
    public static function price(string $price, string $currency = '€'): string
    {
        return number_format((float) $price, 0, ',', '.').$currency;
    }

    public static function area(string $area): string
    {
        return $area.' m²';
    }
}
