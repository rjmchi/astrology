<?php
require_once 'class.convert.php';
require_once 'class.juldate.php';

/**
 * Houses – computes astrological house cusps.
 *
 * Supported systems: Placidus, Koch, Equal, Regiomontanus,
 *                    Porphyry, Meridian, Morinus, Campanus, Topocentric.
 *
 * After construction the following properties are available:
 *   $asc, $mc, $vx (vertex), $ep (equatorial ascendant)
 *
 * Call one of the house-system methods to populate $house[0..11].
 */
class Houses
{
    public float  $asc;
    public float  $mc;
    public float  $vx;
    public float  $ep;

    // Intermediate values reused by house methods
    public float $ci;
    public float $ob;
    public float $ra;
    public float $lat;
    public float $lng;
    public float $gmt;

    /** @var float[] House cusps 0–11 (0 = Asc / 1st, 9 = MC / 10th) */
    public array $house = [];

    public function __construct(int $mm, int $dd, int $yyyy, float $gmt, float $lat, float $lng)
    {
        $jd        = new JulDate($mm, $dd, $yyyy, $gmt);
        $this->lat = deg2rad($lat);
        $this->lng = deg2rad($lng);
        $this->ci  = $jd->ci;
        $this->gmt = $gmt;
        $this->ob  = deg2rad(23.452294 - 0.0130125 * $this->ci);
        $this->ra  = deg2rad(Convert::Mod360(
            (6.6460656 + 2400.0513 * $this->ci + 2.58e-5 * $this->ci * $this->ci + $gmt) * 15.0 - $lng
        ));

        $this->asc = $this->calcAsc();
        $this->mc  = $this->calcMc();
        $this->vx  = $this->calcVx();
        $this->ep  = $this->calcEp();
    }

    // ------------------------------------------------------------------
    // Angles
    // ------------------------------------------------------------------

    public function calcAsc(): float
    {
        $b = atan(cos($this->ra) / (-sin($this->ra) * cos($this->ob) - tan($this->lat) * sin($this->ob)));
        if ($b < 0.0)          { $b += M_PI; }
        if (cos($this->ra) < 0.0) { $b += M_PI; }
        return Convert::Mod360(rad2deg($b));
    }

    public function calcMc(): float
    {
        $m = atan(tan($this->ra) / cos($this->ob));
        if ($m < 0.0)          { $m += M_PI; }
        if ($this->ra > M_PI)  { $m += M_PI; }
        return Convert::Mod360(rad2deg($m));
    }

    public function calcVx(): float
    {
        $ra = $this->ra + M_PI;
        $x  = cos($ra);
        $y  = -sin($ra) * cos($this->ob) - sin($this->ob) / tan($this->lat);
        $v  = atan($x / $y);
        if ($v < 0.0) { $v += M_PI; }
        if ($x < 0.0) { $v += M_PI; }
        return Convert::Mod360(rad2deg($v));
    }

    public function calcEp(): float
    {
        if (!$this->ra) {
            return 0.0;
        }
        $e = atan(cos($this->ra) / (-sin($this->ra) * cos($this->ob)));
        if ($e < 0.0)              { $e += M_PI; }
        if (cos($this->ra) < 0.0) { $e += M_PI; }
        return Convert::Mod360(rad2deg($e));
    }

    // ------------------------------------------------------------------
    // House systems
    // ------------------------------------------------------------------

    public function PlacHouses(): void
    {
        $this->house[0] = $this->asc;
        $this->house[6] = Convert::Mod360($this->house[0] + 180.0);

        $this->house[9] = $this->mc;
        $this->house[3] = Convert::Mod360($this->house[9] + 180.0);

        $this->house[1]  = $this->placHouse(2);
        $this->house[7]  = Convert::Mod360($this->house[1] + 180.0);

        $this->house[2]  = $this->placHouse(3);
        $this->house[8]  = Convert::Mod360($this->house[2] + 180.0);

        $this->house[10] = $this->placHouse(11);
        $this->house[4]  = Convert::Mod360($this->house[10] + 180.0);

        $this->house[11] = $this->placHouse(12);
        $this->house[5]  = Convert::Mod360($this->house[11] + 180.0);
    }

    public function KochHouses(): void
    {
        $a1 = asin(sin($this->ra) * tan($this->lat) * tan($this->ob));

        for ($i = 1; $i < 7; $i++) {
            $d  = Convert::Mod360(60.0 + 30.0 * $i);
            $kn = ($d >= 180.0) ? -1 : 1;
            $a2 = ($d >= 180.0) ? $d / 90.0 - 3.0 : $d / 90.0 - 1.0;

            $a3 = deg2rad(Convert::Mod360(rad2deg($this->ra) + $d + $a2 * rad2deg($a1)));
            $x  = atan(sin($a3) / (cos($a3) * cos($this->ob) - $kn * tan($this->lat) * sin($this->ob)));
            if ($x < 0.0)          { $x += M_PI; }
            if (sin($a3) < 0.0)    { $x += M_PI; }

            $this->house[$i - 1] = Convert::Mod360(rad2deg($x));
            $this->house[$i + 5] = Convert::Mod360(rad2deg($x) + 180.0);
        }
    }

    public function EqualHouses(): void
    {
        $this->house[0] = $this->calcAsc();
        for ($i = 1; $i < 12; $i++) {
            $this->house[$i] = Convert::Mod360($this->house[$i - 1] + 30.0);
        }
    }

    public function RegiomontanusHouses(): void
    {
        for ($i = 1; $i < 7; $i++) {
            $d = deg2rad(60.0 + 30.0 * $i);
            $y = sin($this->ra + $d);
            $x = atan($y / (cos($this->ra + $d) * cos($this->ob)
                - sin($d) * tan($this->lat) * sin($this->ob)));
            if ($x < 0.0) { $x += M_PI; }
            if ($y < 0.0) { $x += M_PI; }

            $this->house[$i - 1] = Convert::Mod360(rad2deg($x));
            $this->house[$i + 5] = Convert::Mod360(rad2deg($x) + 180.0);
        }
    }

    public function PorphyryHouses(): void
    {
        // Descending arc (IC → Asc)
        $x = $this->asc - $this->mc;
        if ($x < 0.0) { $x += 360.0; }
        $y = $x / 3.0;
        for ($i = 1; $i < 3; $i++) {
            $this->house[$i + 3] = Convert::Mod360(180.0 + $this->mc + $i * $y);
        }

        // Ascending arc (Asc → MC+180)
        $x = Convert::Mod360(180.0 + $this->mc) - $this->asc;
        if ($x < 0.0) { $x += 360.0; }
        $this->house[0] = $this->asc;
        $y = $x / 3.0;
        for ($i = 1; $i < 4; $i++) {
            $this->house[$i] = Convert::Mod360($this->asc + $i * $y);
        }

        // Opposite cusps
        for ($i = 0; $i < 6; $i++) {
            $this->house[$i + 6] = Convert::Mod360($this->house[$i] + 180.0);
        }
    }

    public function MeridianHouses(): void
    {
        for ($i = 1; $i < 7; $i++) {
            $d = deg2rad(60.0 + 30.0 * $i);
            $y = sin($this->ra + $d);
            $x = atan($y / (cos($this->ra + $d) * cos($this->ob)));
            if ($x < 0.0) { $x += M_PI; }
            if ($y < 0.0) { $x += M_PI; }

            $this->house[$i - 1] = Convert::Mod360(rad2deg($x));
            $this->house[$i + 5] = Convert::Mod360(rad2deg($x) + 180.0);
        }
    }

    public function MorinusHouses(): void
    {
        for ($i = 1; $i < 7; $i++) {
            $d = deg2rad(60.0 + 30.0 * $i);
            $y = sin($this->ra + $d) * cos($this->ob);
            $x = atan($y / cos($this->ra + $d));
            if ($x < 0.0) { $x += M_PI; }
            if ($y < 0.0) { $x += M_PI; }

            $this->house[$i - 1] = Convert::Mod360(rad2deg($x));
            $this->house[$i + 5] = Convert::Mod360(rad2deg($x) + 180.0);
        }
    }

    public function CampanusHouses(): void
    {
        for ($i = 1; $i < 7; $i++) {
            $ko = deg2rad(60.000001 + 30.0 * $i);
            $dn = atan(tan($ko) * cos($this->lat));
            if ($dn < 0.0)      { $dn += M_PI; }
            if (sin($ko) < 0.0) { $dn += M_PI; }

            $y = sin($this->ra + $dn);
            $x = atan($y / (cos($this->ra + $dn) * cos($this->ob)
                - sin($dn) * tan($this->lat) * sin($this->ob)));
            if ($x < 0.0) { $x += M_PI; }
            if ($y < 0.0) { $x += M_PI; }

            $this->house[$i - 1] = Convert::Mod360(rad2deg($x));
            $this->house[$i + 5] = Convert::Mod360(rad2deg($x) + 180.0);
        }
    }

    public function TopocentricHouses(): void
    {
        $rad30 = deg2rad(30.0);
        $this->house[9] = $this->mc;
        $this->house[3] = Convert::Mod360($this->house[9] + 180.0);

        $p1 = atan(tan($this->lat) / 3.0);
        $p2 = atan(tan($this->lat) / 3.0 * 2.0);

        $ra = Convert::Mod360($this->ra + $rad30);
        $this->house[10] = $this->topHouse($p1, $ra);
        $this->house[4]  = Convert::Mod360($this->house[10] + 180.0);

        $ra = Convert::Mod360($ra + $rad30);
        $this->house[11] = $this->topHouse($p2, $ra);
        $this->house[5]  = Convert::Mod360($this->house[11] + 180.0);

        $ra = Convert::Mod360($ra + $rad30);
        $this->house[0] = $this->asc;
        $this->house[6] = Convert::Mod360($this->house[0] + 180.0);

        $ra = Convert::Mod360($ra + $rad30);
        $this->house[1] = $this->topHouse($p2, $ra);
        $this->house[7] = Convert::Mod360($this->house[1] + 180.0);

        $ra = Convert::Mod360($ra + $rad30);
        $this->house[2] = $this->topHouse($p1, $ra);
        $this->house[8] = Convert::Mod360($this->house[2] + 180.0);
    }

    // ------------------------------------------------------------------
    // Private helpers
    // ------------------------------------------------------------------

    private function placHouse(int $h): float
    {
        $params = [
            2  => ['ff' => 1.5,  'k' =>  1],
            3  => ['ff' => 3.0,  'k' =>  1],
            11 => ['ff' => 3.0,  'k' => -1],
            12 => ['ff' => 1.5,  'k' => -1],
        ];
        ['ff' => $ff, 'k' => $k] = $params[$h];

        $r1 = $this->ra;
        for ($i = 1; $i <= 10; $i++) {
            $xx = acos($k * sin($r1) * tan($this->ob) * tan($this->lat));
            if ($xx < 0.0) { $xx += M_PI; }
            $r1 = ($k === 1)
                ? $this->ra + M_PI - ($xx / $ff)
                : $this->ra + ($xx / $ff);
        }

        $lo = atan(tan($r1) / cos($this->ob));
        if ($lo < 0.0)      { $lo += M_PI; }
        if (sin($r1) < 0.0) { $lo += M_PI; }
        return Convert::Mod360(rad2deg($lo));
    }

    private function topHouse(float $lat, float $ra): float
    {
        $x = atan(tan($lat) / cos($ra));
        $y = $x + $this->ob;
        $h = atan(cos($x) * tan($ra) / cos($y));
        if ($h < 0.0)      { $h += M_PI; }
        if (sin($ra) < 0.0){ $h += M_PI; }
        return Convert::Mod360(rad2deg($h));
    }
}