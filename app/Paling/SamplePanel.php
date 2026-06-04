<?php

namespace App\Paling;

class SamplePanel
{
    /**
     * @return array{name: string, widthMm: int, heightMm: int, picketMm: int, gapMm: int, rails: int}
     */
    public static function garden(): array
    {
        return [
            'name' => 'Sample garden paling',
            'widthMm' => 1420,
            'heightMm' => 900,
            'picketMm' => 70,
            'gapMm' => 20,
            'rails' => 2,
        ];
    }
}
