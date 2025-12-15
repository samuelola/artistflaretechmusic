<?php

namespace App\Enum;

class Plan
{
    const Basic = 'Basic';
    const EasyBuy = 'Easy-Buy';
    const FlarePro = 'FlarePro';
    const StandardLabel = 'Standard-Label';

    public static function groups(): array
    {
        return [
            'yearly' => [self::Basic, self::StandardLabel],
            'forever' => [self::FlarePro],
            'monthly' => [self::EasyBuy],
        ];
    }
}
