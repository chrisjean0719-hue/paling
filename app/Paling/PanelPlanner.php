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
        $gravel = $filled['gravelMm'] ?? 0;
        $pickets = Layout::picketCount($width, $picket, $gap);
        $picketLength = $height - $gravel;
        $boardLength = $gravel === 0 ? 0 : $width;

        return [
            'name' => trim($filled['name']),
            'widthMm' => $width,
            'heightMm' => $height,
            'picketMm' => $picket,
            'gapMm' => $gap,
            'rails' => $rails,
            'gravelMm' => $gravel,
            'picketLengthMm' => $picketLength,
            'pickets' => $pickets,
            'boardLengthMm' => $boardLength,
            'woodLengthMm' => Layout::woodLengthMm($width, $picketLength, $pickets, $rails, $boardLength),
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
