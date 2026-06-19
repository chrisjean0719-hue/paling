<?php

namespace App\Paling;

class Elevation
{
    public const LABEL_BAND = 16;

    public const RAIL_BAND = 40;

    public const POST_FACE = 80;

    private const PICKET = '#c4a574';

    private const RAIL = '#8d6b4a';

    private const BOARD = '#8a8175';

    private const POST = '#6e5344';

    private const INK = '#2b241c';

    /**
     * @param  array<string, int|string>  $plan
     */
    public static function svg(array $plan): string
    {
        $width = $plan['widthMm'];
        $height = $plan['heightMm'];
        $point = $plan['pointMm'];
        $set = $plan['setMm'];
        $originX = $plan['postCount'] === 0 ? 0 : self::POST_FACE;
        $originY = self::LABEL_BAND + $point;
        $pictureWidth = $originX + $width + ($plan['postCount'] === 0 ? 0 : self::POST_FACE);
        $pictureHeight = self::LABEL_BAND + $point + $height + $set;
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$pictureWidth.' '.$pictureHeight.'" width="'.$pictureWidth.'mm" height="'.$pictureHeight.'mm">',
            '  <title>'.self::escape($plan['name']).'</title>',
            '  <text x="0" y="12" font-size="11" fill="'.self::INK.'">Paling</text>',
        ];
        if ($plan['postCount'] === 2) {
            $lines[] = self::rect(0, $originY, self::POST_FACE, $plan['postLengthMm'], self::POST);
            $lines[] = self::rect($originX + $width, $originY, self::POST_FACE, $plan['postLengthMm'], self::POST);
        }
        $lines = array_merge($lines, self::pickets($plan, $originX, $originY));
        if ($plan['gravelMm'] > 0) {
            $lines[] = self::rect($originX, $originY + $plan['picketLengthMm'], $width, $plan['gravelMm'], self::BOARD);
        }
        $lines = array_merge($lines, self::rails($plan, $originX, $originY));
        $lines[] = '</svg>';
        $lines[] = '';

        return implode("\n", $lines);
    }

    public static function fileName(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return ($slug === '' ? 'panel' : $slug).'-elevation.svg';
    }

    /**
     * @param  array<string, int|string>  $plan
     * @return list<string>
     */
    private static function pickets(array $plan, int $originX, int $originY): array
    {
        $lines = [];
        $step = $plan['picketMm'] + $plan['gapMm'];
        for ($index = 0; $index < $plan['pickets']; $index++) {
            $x = $originX + $index * $step;
            if ($plan['pointMm'] > 0) {
                $peak = $x + intdiv($plan['picketMm'], 2);
                $lines[] = '  <polygon points="'.$x.','.$originY.' '.($x + $plan['picketMm']).','.$originY.' '.$peak.','.($originY - $plan['pointMm']).'" fill="'.self::PICKET.'"/>';
            }
            $lines[] = self::rect($x, $originY, $plan['picketMm'], $plan['picketLengthMm'], self::PICKET);
        }

        return $lines;
    }

    /**
     * @param  array<string, int|string>  $plan
     * @return list<string>
     */
    private static function rails(array $plan, int $originX, int $originY): array
    {
        $lines = [];
        $slot = intdiv($plan['picketLengthMm'], $plan['rails'] + 1);
        $band = min(self::RAIL_BAND, $slot);
        for ($index = 1; $index <= $plan['rails']; $index++) {
            $center = intdiv($plan['picketLengthMm'] * $index, $plan['rails'] + 1);
            $lines[] = self::rect($originX, $originY + $center - intdiv($band, 2), $plan['widthMm'], $band, self::RAIL);
        }

        return $lines;
    }

    private static function rect(int $x, int $y, int $width, int $height, string $fill): string
    {
        return '  <rect x="'.$x.'" y="'.$y.'" width="'.$width.'" height="'.$height.'" fill="'.$fill.'"/>';
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
