<?php

namespace App\Enums\Product;

enum sectionProductEnum: string
{
    case CABALLERO = 'caballero';
    case DAMA = 'dama';
    case NINO = 'niño';
    case NINA = 'niña';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
