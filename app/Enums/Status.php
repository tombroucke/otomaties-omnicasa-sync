<?php

namespace Otomaties\Omnicasa\Enums;

enum Status: int
{
    case PROSPECT = 0;
    case ACTIVE = 1;
    case SOLD = 2;
    case CANCELLED = 3;
    case RENTED = 4;

    public function syncable(): bool
    {
        return in_array($this, [
            self::ACTIVE,
            self::SOLD,
            self::RENTED,
        ]);
    }

    public function active(): bool
    {
        return $this === self::ACTIVE;
    }
}
