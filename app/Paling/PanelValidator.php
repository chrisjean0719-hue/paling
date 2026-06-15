<?php

namespace App\Paling;

class PanelValidator
{
    /**
     * @return list<string>
     */
    public static function validate(mixed $panel): array
    {
        if (! is_array($panel) || array_is_list($panel)) {
            return ['panel: Panel must be an object'];
        }

        $errors = [];
        $name = $panel['name'] ?? null;
        if (! is_string($name) || trim($name) === '') {
            $errors[] = 'name: Panel needs a name';
        }
        if (! self::inRange($panel['widthMm'] ?? null, Sizes::MIN_WIDTH_MM, Sizes::MAX_WIDTH_MM)) {
            $errors[] = 'widthMm: widthMm must be an integer from '.Sizes::MIN_WIDTH_MM.' to '.Sizes::MAX_WIDTH_MM;
        }
        if (! self::inRange($panel['heightMm'] ?? null, Sizes::MIN_HEIGHT_MM, Sizes::MAX_HEIGHT_MM)) {
            $errors[] = 'heightMm: heightMm must be an integer from '.Sizes::MIN_HEIGHT_MM.' to '.Sizes::MAX_HEIGHT_MM;
        }
        if (! self::inRange($panel['picketMm'] ?? null, Sizes::MIN_PICKET_MM, Sizes::MAX_PICKET_MM)) {
            $errors[] = 'picketMm: picketMm must be an integer from '.Sizes::MIN_PICKET_MM.' to '.Sizes::MAX_PICKET_MM;
        }
        if (! self::inRange($panel['gapMm'] ?? null, Sizes::MIN_GAP_MM, Sizes::MAX_GAP_MM)) {
            $errors[] = 'gapMm: gapMm must be an integer from '.Sizes::MIN_GAP_MM.' to '.Sizes::MAX_GAP_MM;
        }
        if (! self::inRange($panel['rails'] ?? null, Sizes::MIN_RAILS, Sizes::MAX_RAILS)) {
            $errors[] = 'rails: rails must be an integer from '.Sizes::MIN_RAILS.' to '.Sizes::MAX_RAILS;
        }
        $gravel = $panel['gravelMm'] ?? null;
        if ($gravel !== null && ! self::inRange($gravel, Sizes::MIN_GRAVEL_MM, Sizes::MAX_GRAVEL_MM)) {
            $errors[] = 'gravelMm: gravelMm must be an integer from '.Sizes::MIN_GRAVEL_MM.' to '.Sizes::MAX_GRAVEL_MM;
        }
        $point = $panel['pointMm'] ?? null;
        if ($point !== null && ! self::inRange($point, Sizes::MIN_POINT_MM, Sizes::MAX_POINT_MM)) {
            $errors[] = 'pointMm: pointMm must be an integer from '.Sizes::MIN_POINT_MM.' to '.Sizes::MAX_POINT_MM;
        }
        $set = $panel['setMm'] ?? null;
        if ($set !== null && ! self::inRange($set, Sizes::MIN_SET_MM, Sizes::MAX_SET_MM)) {
            $errors[] = 'setMm: setMm must be an integer from '.Sizes::MIN_SET_MM.' to '.Sizes::MAX_SET_MM;
        }
        if ($errors !== []) {
            return $errors;
        }

        $count = Layout::picketCount($panel['widthMm'], $panel['picketMm'], $panel['gapMm']);
        if ($count === null) {
            $errors[] = 'widthMm: the pickets do not divide the panel into whole millimetres';
        }
        if (is_int($gravel) && $panel['heightMm'] - $gravel < Sizes::MIN_PICKET_LENGTH_MM) {
            $errors[] = 'gravelMm: the gravel board leaves the pickets under '.Sizes::MIN_PICKET_LENGTH_MM.' mm';
        }

        return $errors;
    }

    private static function inRange(mixed $value, int $min, int $max): bool
    {
        return is_int($value) && $value >= $min && $value <= $max;
    }
}
