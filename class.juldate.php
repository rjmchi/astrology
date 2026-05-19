<?php
/**
 * JulDate – Julian Date calculator.
 *
 * Given a Gregorian calendar date and a GMT offset, computes:
 *   $jd  – the Julian Day Number
 *   $ci  – Julian centuries from J1900 (used as the time argument
 *           throughout the planet/house calculations)
 */
class JulDate
{
    public int   $jd;
    public float $ci;

    public function __construct(int $m, int $d, int $y, float $gmt)
    {
        $im = 12 * ($y + 4800) + $m - 3;
        $j  = (int) ((2 * ($im % 12) + 7 + 365 * $im) / 12);
        $j  = $j + $d + (int) ($im / 48) - 32083;

        // Gregorian correction (post-1582 dates)
        $this->jd = ($j > 2299171)
            ? $j + (int) ($im / 4800) - (int) ($im / 1200) + 38
            : $j;

        // Julian centuries from J1900.0
        $this->ci = (($this->jd - 2415020.0) + $gmt / 24.0 - 0.5) / 36525.0;
    }

    /**
     * Convert the stored Julian Day back to a "MM/DD/YYYY" string.
     */
    public function getString(): string
    {
        $l     = (int) ($this->jd + 0.5) + 68569;
        $n     = (int) (4 * $l / 146097);
        $l     = $l - (int) ((146097 * $n + 3) / 4);
        $it    = (int) (4000 * ($l + 1) / 1461001);
        $l     = $l - (int) (1461 * $it / 4) + 31;
        $month = (int) (80 * $l / 2447);
        $day   = $l - (int) (2447 * $month / 80);
        $l     = (int) ($month / 11);
        $month = $month + 2 - 12 * $l;
        $year  = 100 * ($n - 49) + $it + $l;

        return sprintf('%d/%d/%d', $month, $day, $year);
    }
}
