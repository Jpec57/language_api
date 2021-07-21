<?php


namespace App\Enum;


abstract class SRSLevelEnum
{

    const NEW = 0;
    const APPRENTICE = 1;
    const APPRENTICE2 = 2;
    const APPRENTICE3 = 3;
    const APPRENTICE4 = 4;
    const GURU = 5;
    const GURU2 = 6;
    const MASTER = 7;
    const ENLIGHTENED = 8;
    const BOSS = 9;
    const BURNED = 10;


    public static function getDateIntervalDifferenceAccordingToLevel(int $level): \DateInterval{
        return match ($level) {
            self::NEW => new \DateInterval('+1 hour'),
            self::APPRENTICE => new \DateInterval('+4 hour'),
            self::APPRENTICE2 => new \DateInterval('+8 hour'),
            self::APPRENTICE3 => new \DateInterval('+1 day'),
            self::APPRENTICE4 => new \DateInterval('+2 days'),
            self::GURU => new \DateInterval('+1 week'),
            self::GURU2 => new \DateInterval('+2 weeks'),
            self::MASTER => new \DateInterval('+1 month'),
            self::ENLIGHTENED => new \DateInterval('+4 months'),
            self::BOSS => new \DateInterval('+1 year'),
            self::BURNED => new \DateInterval('+5 years'),
            default => new \DateInterval('+4 hours'),
        };
    }

}