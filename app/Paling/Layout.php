<?php

namespace App\Paling;

class Layout
{
    public static function picketCount(int $widthMm, int $picketMm, int $gapMm): ?int
    {
        $module = $picketMm + $gapMm;
        $span = $widthMm + $gapMm;
        if ($module === 0 || $span % $module !== 0) {
            return null;
        }
        $count = intdiv($span, $module);

        return $count >= 2 ? $count : null;
    }

    public static function pointWoodMm(int $pickets, int $pointMm): int
    {
        return $pickets * $pointMm;
    }

    public static function postLengthMm(int $heightMm, int $setMm): int
    {
        return $heightMm + $setMm;
    }

    public static function postWoodMm(int $postLengthMm): int
    {
        return 2 * $postLengthMm;
    }

    public static function woodLengthMm(int $widthMm, int $picketLengthMm, int $pickets, int $rails, int $boardLengthMm = 0, int $pointWoodMm = 0, int $postWoodMm = 0): int
    {
        return $pickets * $picketLengthMm + $rails * $widthMm + $boardLengthMm + $pointWoodMm + $postWoodMm;
    }
}
