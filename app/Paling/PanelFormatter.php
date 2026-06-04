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
            "wood: {$plan['woodLengthMm']} mm",
        ]);
    }
}
