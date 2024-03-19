<?php

namespace Otomaties\Omnicasa\Enums;

enum Goal: int
{
    case SALE = 0;
    case RENT = 1;

    public function label(): string
    {
        return match ($this) {
            Goal::SALE => __('For sale', 'otomaties-omnicasa-sync'),
            Goal::RENT => __('For rent', 'otomaties-omnicasa-sync'),
        };
    }
}
