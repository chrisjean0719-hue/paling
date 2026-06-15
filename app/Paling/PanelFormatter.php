<?php

namespace App\Paling;

class PanelFormatter
{
    /**
     * @param  array<string, int|string>  $plan
     */
    public static function format(array $plan): string
    {
        return implode("\n", [
            $plan['name'],
            "panel: {$plan['widthMm']} mm by {$plan['heightMm']} mm",
            "pickets: {$plan['pickets']} at {$plan['picketMm']} mm, gap {$plan['gapMm']} mm",
            "rails: {$plan['rails']}",
            $plan['gravelMm'] === 0 ? 'gravel: none' : "gravel: {$plan['gravelMm']} mm, board {$plan['boardLengthMm']} mm",
            $plan['pointMm'] === 0 ? 'point: none' : "point: {$plan['pointMm']} mm, {$plan['pointWoodMm']} mm extra",
            $plan['setMm'] === 0 ? 'posts: none' : "posts: {$plan['postCount']} at {$plan['postLengthMm']} mm, set {$plan['setMm']} mm",
            "wood: {$plan['woodLengthMm']} mm",
        ]);
    }
}
