<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TreatmentPlantType extends Enum
{
    const CentralizedWWTP = 1;
    const DecentralizedWWTP = 2;
    const FSTP = 3;

    public static function toEnumArray()
    {
        return [
            self::CentralizedWWTP => 'Centralized WWTP',
            self::DecentralizedWWTP => 'Decentralized WWTP',
            self::FSTP => 'FSTP',
        ];
    }
}

