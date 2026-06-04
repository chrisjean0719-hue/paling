<?php

namespace App\Paling;

class PanelPlanner
{
    /**
     * @return array<string, int|string>
     */
    public static function plan(mixed $panel): array
    {
        $filled = self::withDefaults($panel);
        $errors = PanelValidator::validate($filled);
        if ($errors !== []) {
            throw new PanelException($errors);
        }

        $width = $filled['widthMm'];
        $height = $filled['heightMm'];
        $picket = $filled['picketMm'];
        $gap = $filled['gapMm'];
        $rails = $filled['rails'];
        $pickets = Layout::picketCount($width, $picket, $gap);

        return [
            'name' => trim($filled['name']),
            'widthMm' => $width,
            'heightMm' => $height,
            'picketMm' => $picket,
            'gapMm' => $gap,
            'rails' => $rails,
            'pickets' => $pickets,
            'woodLengthMm' => Layout::woodLengthMm($width, $height, $pickets, $rails),
        ];
    }

    /**
     * @return mixed
     */
    private static function withDefaults(mixed $panel): mixed
    {
        if (! is_array($panel) || array_is_list($panel)) {
            return $panel;
        }
        $filled = $panel;
        $filled['picketMm'] ??= Sizes::DEFAULT_PICKET_MM;
        $filled['gapMm'] ??= Sizes::DEFAULT_GAP_MM;
        $filled['rails'] ??= Sizes::DEFAULT_RAILS;

        return $filled;
    }
}
