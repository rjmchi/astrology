<?php
/**
 * Coordinates
 *
 * Thin stateful wrapper around the pure static functions in Convert,
 * used throughout the planet-calculation pipeline so intermediate
 * rectangular and polar values can be passed by reference.
 *
 * Properties after RecToPol: $a (angle, radians), $r (radius)
 * Properties after PolToRec: $x, $y
 */
class Coordinates
{
    public float $a = 0.0;
    public float $r = 0.0;
    public float $x = 0.0;
    public float $y = 0.0;

    /** Convert rectangular (x, y) → polar, storing result in $this->a and $this->r. */
    public function RecToPol(float $x, float $y): void
    {
        if ($y == 0.0) {
            $y = SMALL;
        }
        $this->r = sqrt($x * $x + $y * $y);
        $a = atan($y / $x);
        if ($a < 0) { $a += M_PI; }
        if ($y < 0) { $a += M_PI; }
        $this->a = $a;
    }

    /** Convert polar (a, r) → rectangular, storing result in $this->x and $this->y. */
    public function PolToRec(float $a, float $r): void
    {
        $this->x = $r * cos($a);
        $this->y = $r * sin($a);
    }
}