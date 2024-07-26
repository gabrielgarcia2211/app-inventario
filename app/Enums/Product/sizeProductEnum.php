<?php

namespace App\Enums\Product;

enum sizeProductEnum: string
{
    case XS = 'XS';
    case S = 'S';
    case M = 'M';
    case L = 'L';
    case XL = 'XL';
    case XXL = 'XXL';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}