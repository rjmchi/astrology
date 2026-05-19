<?php
/**
 * Various astronomical/geographic conversion utilities.
 *
 * Methods:
 *   DecToLong      - decimal value → longitude string
 *   DecToLat       - decimal value → latitude string
 *   DecToTime      - decimal value → time (12 or 24-hour)
 *   DecToZod       - decimal degrees → zodiac sign string
 *   DecToZodGlyph  - decimal degrees → zodiac sign with glyph span
 *   Mod360         - normalise value to [0, 360)
 *   Mod2Pi         - normalise value to [0, 2π)
 *
 * Note: RecToPol / PolToRec have been removed from this class.
 * They are now handled exclusively by the Coordinates class.
 */


define("SMALL",  1.7453e-09);
class Convert {

    /**
     * Format a decimal time value as a time string.
     *
     * @param float $decTime   Hours as a decimal (e.g. 13.5 = 1:30 PM).
     * @param int   $clockType 12 or 24.
     */

    public static function DecToTime (float $decTime, int $clockType) : string {
        $hours = (int) $decTime;
        $min   = (int) (($decTime - $hours) * 60.0 + 0.5);
 
        if ($clockType === 12) {
            $amPm = $hours >= 12 ? 'P' : 'A';
            if ($hours > 12) {
                $hours -= 12;
            } elseif ($hours === 0) {
                $hours = 12;
            }
            return sprintf('%d:%02d %sM', $hours, $min, $amPm);
        }
        return sprintf('%d:%02d', $hours, $min);    
    }

    /**
     * Format a decimal longitude as e.g. "71W04".
     * Positive = West, negative = East (traditional astrological convention).
     */

    public static function DecToLong(float $decLng) : string {
        $dir    = $decLng < 0 ? 'E' : 'W';
        $decLng = abs($decLng);
        $deg    = (int) $decLng;
        $min    = (int) (($decLng - $deg) * 60.0 + 0.5);
        return sprintf('%d%s%02d', $deg, $dir, $min);
    }

    /**
     * Format a decimal latitude as e.g. "42N21".
     * Positive = North, negative = South.
     */
    public static function DecToLat(float $decLat): string
    {
        $dir    = $decLat < 0 ? 'S' : 'N';
        $decLat = abs($decLat);
        $deg    = (int) $decLat;
        $min    = (int) (($decLat - $deg) * 60.0 + 0.5);
        return sprintf('%d%s%02d', $deg, $dir, $min);
    }


    public static function Mod360( float $x) : float  {
    /** Normalise $x to [0, 360). */        
    /*  returns result within circle    */

        if (!is_numeric($x)) {
            echo '--';
            die ($x);
            echo ' not numeric --';
        }
        $result = fmod($x, 360.0);
        return $result < 0 ? $result + 360.0 : $result;
    }

    public static function Mod2Pi(float $x) : float {
    /** Normalise $x to [0, 2π). */
    /*  returns result within circle in radians    */
        $twoPi = M_PI * 2;
        $result = fmod($x, $twoPi);
        return $result < 0 ? $result + $twoPi : $result;
    }

    public static function DecToZod(float $place) :string {
        static $signNames = [
            ' ARI ', ' TAU ', ' GEM ', ' CAN ', ' LEO ', ' VIR ',
            ' LIB ', ' SCO ', ' SAG ', ' CAP ', ' AQU ', ' PIC ',
        ];
        [$s, $d, $m] = self::zodiacParts($place);
        return sprintf('%d%s%02d', $d, $signNames[$s], $m);
    }


    /**
     * Convert decimal degrees to a zodiac position string using font glyphs,
     * e.g. '14 <span class="glyph"> b </span> 32'.
     */

    public static function DecToZodGlyph(float $place) : string{
        static $signGlyphs = [
            ' a ', ' b ', ' c ', ' d ', ' e ', ' f ',
            ' g ', ' h ', ' i ', ' j ', ' k ', ' l ',
            ];
        [$s, $d, $m] = self::zodiacParts($place);
        return sprintf('%d <span class="glyph">%s</span> %02d', $d, $signGlyphs[$s], $m);
    }


    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------
 
    /**
     * Return [signIndex, degrees, minutes] for a decimal ecliptic longitude.
     *
     * @return array{int, int, int}
     */
    private static function zodiacParts(float $place): array {
        $s = (int) ($place / 30.0);
        $place -= $s * 30.0;
        $d = (int) $place;
        $m = (int) (($place - $d) * 60.0 + 0.5);
 
        if ($m >= 60) { $m -= 60; $d++; }
        if ($d >= 30) { $d -= 30; $s++; }
        if ($s >= 12) { $s -= 12; }
 
        return [$s, $d, $m];
    }
}
