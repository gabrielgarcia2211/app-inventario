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

    public function getPercentage(): float
    {
        return match ($this) {
            self::MEN => 0.10,    // 10% adjustment
            self::WOMEN => 0.15,  // 15% adjustment
            self::BOYS => 0.05,   // 5% adjustment
            self::GIRLS => 0.08,  // 8% adjustment
        };
    }
}
