<?php

namespace App\Paling;

class Compare
{
    /**
     * @param  array<string, int|string>  $left
     * @param  array<string, int|string>  $right
     * @return array{pickets: array{0: int, 1: int}, woodLengthMm: array{0: int, 1: int}}
     */
    public static function panels(array $left, array $right): array
    {
        return [
            'pickets' => [$left['pickets'], $right['pickets']],
            'woodLengthMm' => [$left['woodLengthMm'], $right['woodLengthMm']],
        ];
    }

    /**
     * @param  array<string, int|string>  $left
     * @param  array<string, int|string>  $right
     * @param  array{pickets: array{0: int, 1: int}, woodLengthMm: array{0: int, 1: int}}  $diff
     */
    public static function format(array $left, array $right, array $diff): string
    {
        return implode("\n", [
            $left['name'].' vs '.$right['name'],
            'pickets: '.$diff['pickets'][0].' vs '.$diff['pickets'][1],
            'wood: '.$diff['woodLengthMm'][0].' mm vs '.$diff['woodLengthMm'][1].' mm',
        ]);
    }
}
