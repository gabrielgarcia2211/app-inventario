<?php

namespace App\Enums;

enum categoryProductEnum: string
{
    case P = 'Pueblos';
    case CA = 'Ciudad A';
    case CB = 'Ciudad B';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getPercentage(): float
    {
        return match ($this) {
            self::P => 0.10,    // 10% adjustment
            self::CA => 0.0,  // 15% adjustment
            self::CB => 0.10,   // 5% adjustment
        };
    }
}
