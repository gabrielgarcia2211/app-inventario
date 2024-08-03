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
    case SIZE_2 = '2';
    case SIZE_4 = '4';
    case SIZE_6 = '6';
    case SIZE_8 = '8';
    case SIZE_10 = '10';
    case SIZE_12 = '12';
    case SIZE_14 = '14';
    case SIZE_16 = '16';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getSizesFor($category): array
    {
        if (in_array(strtolower($category), ['niño', 'niña'])) {
            return [
                self::SIZE_2->value,
                self::SIZE_4->value,
                self::SIZE_6->value,
                self::SIZE_8->value,
                self::SIZE_10->value,
                self::SIZE_12->value,
                self::SIZE_14->value,
                self::SIZE_16->value,
            ];
        } else {
            return [
                self::XS->value,
                self::S->value,
                self::M->value,
                self::L->value,
                self::XL->value,
                self::XXL->value,
            ];
        }
    }
}