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
        $point = $filled['pointMm'] ?? 0;
        $set = $filled['setMm'] ?? 0;
        $pickets = Layout::picketCount($width, $picket, $gap);
        $picketLength = $height - $gravel;
        $boardLength = $gravel === 0 ? 0 : $width;
        $pointWood = $point === 0 ? 0 : Layout::pointWoodMm($pickets, $point);
        $postLength = $set === 0 ? 0 : Layout::postLengthMm($height, $set);
        $postWood = $set === 0 ? 0 : Layout::postWoodMm($postLength);

        return [
            'name' => trim($filled['name']),
            'widthMm' => $width,
            'heightMm' => $height,
            'picketMm' => $picket,
            'gapMm' => $gap,
            'rails' => $rails,
            'gravelMm' => $gravel,
            'pointMm' => $point,
            'setMm' => $set,
            'picketLengthMm' => $picketLength,
            'pickets' => $pickets,
            'boardLengthMm' => $boardLength,
            'pointWoodMm' => $pointWood,
            'postCount' => $set === 0 ? 0 : 2,
            'postLengthMm' => $postLength,
            'woodLengthMm' => Layout::woodLengthMm($width, $picketLength, $pickets, $rails, $boardLength, $pointWood, $postWood),
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
