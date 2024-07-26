<?php

namespace App\Enums\Client;

enum nameClientEnum: string
{
    case C1 = 'C1';
    case C2 = 'C2';
    case C3 = 'C3';
    case C4 = 'C4';
    case C5 = 'C5';
    case C6 = 'C6';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}