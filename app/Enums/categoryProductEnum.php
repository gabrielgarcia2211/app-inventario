<?php

namespace App\Enums;

enum categoryProductEnum: string
{
    case MEN = 'Men';
    case WOMEN = 'Women';
    case BOYS = 'Boys';
    case GIRLS = 'Girls';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
