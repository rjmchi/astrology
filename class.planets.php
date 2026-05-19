<?php
require_once 'class.convert.php';
require_once 'class.juldate.php';
require_once 'class.planet.php';
require_once 'class.coordinates.php';

/**
 * Planets – computes heliocentric / geocentric ecliptic positions for the
 * nine classical planets plus Moon, True North Node, South Node and Mean Node.
 *
 * Usage:
 *   $planets = new Planets($month, $day, $year, $gmtOffset);
 *   $sun     = $planets->planets[0];   // Planet object
 *   echo $sun->long;                   // decimal degrees
 *
 * After construction, $planets->planets is indexed:
 *   0 Sun  1 Moon  2 Mercury  3 Venus  4 Mars  5 Jupiter
 *   6 Saturn  7 Uranus  8 Neptune  9 Pluto
 *   10 North Node  11 South Node  12 Mean Node
 */
class Planets
{
    // ------------------------------------------------------------------
    // Orbital element tables  (index 0-8 → Sun…Pluto)
    // Each row: [base_value, per_century, per_century²]
    // ------------------------------------------------------------------

    /** Mean anomaly */
    private const MA = [
        [358.47584,  35999.0498,   -0.00015],
        [102.27938, 149472.515,     0.0    ],
        [212.6032,   58517.8039,    0.0013 ],
        [319.5293,   19139.8585,    0.00018],
        [225.4928,    3033.6879,    0.0    ],
        [174.2153,    1223.50796,   0.0    ],
        [ 74.1757,     427.2742,    0.0    ],
        [ 30.13294,    240.45516,   0.0    ],
        [229.781,      145.1781,    0.0    ],
    ];

    /** Eccentricity */
    private const ECC = [
        [0.016751, -0.000041,  0.0    ],
        [0.205614,  0.00002,   0.0    ],
        [0.00682,  -0.00005,   0.0    ],
        [0.09331,   0.00009,   0.0    ],
        [0.04838,   0.00002,   0.0    ],
        [0.05423,  -0.0002,    0.0    ],
        [0.04682,   0.00042,   0.0    ],
        [0.00913,  -0.00127,   0.0    ],
        [0.24797,   0.002898,  0.0    ],
    ];

    /** Semi-major axis (AU) */
    private const AU = [
        1.00000013, 0.387098, 0.7233,   1.52369, 5.2029,
        9.5525,    19.2215,  30.11375, 39.539,
    ];

    /** Argument of perihelion */
    private const AOP = [
        [101.22083,  1.71918,    0.00045],
        [ 28.75375,  0.37028,    0.00012],
        [ 54.3842,   0.5082,    -0.0014 ],
        [285.43176,  1.06977,    0.00013],
        [273.393,    1.3383,     0.0    ],
        [338.9117,  -0.3167,     0.0    ],
        [ 95.6863,   2.0508,     0.0    ],
        [284.1683,  -21.6329,    0.0    ],
        [113.5366,   0.2086,     0.0    ],
    ];

    /** Ascending node */
    private const AN = [
        [  0.0,       0.0,      0.0    ],
        [ 47.14594,   1.1852,   0.00017],
        [ 75.7796,    0.8999,   0.0004 ],
        [ 48.78644,   0.77099,  0.0    ],
        [ 99.4198,    1.0583,   0.0    ],
        [112.8261,    0.8259,   0.0    ],
        [ 73.5222,    0.5242,   0.0    ],
        [130.68415,   1.1005,   0.0    ],
        [108.944,     1.3739,   0.0    ],
    ];

    /** Inclination */
    private const INCL = [
        [ 0.0,      0.0,      0.0   ],
        [ 7.00288,  0.00186, -0.00001],
        [ 3.3936,   0.001,    0.0   ],
        [ 1.85033, -0.00068,  0.0001],
        [ 1.3097,  -0.0052,   0.0   ],
        [ 2.4908,  -0.0047,   0.0   ],
        [ 0.7726,   0.0001,   0.0   ],
        [ 1.7794,  -0.0098,   0.0   ],
        [17.1514,  -0.0161,   0.0   ],
    ];

    // Perturbation harmonic tables for outer planets (Jupiter…Pluto)
    private const JH = [
        -0.001, -0.0005, 0.0045, 0.0051, 581.7, -9.7, -0.0005, 2510.7, -12.5,
        -0.0026, 1313.7, -61.4, 0.0013, 2370.79, -24.6, -0.0013, 3599.3, 37.7,
        -0.001, 2574.7, 31.4, -0.00096, 6708.2, -114.5, -0.0006, 5499.4, -74.97,
        -0.0013, 1419.0, 54.2, 0.0006, 6339.3, -109.0, 0.0007, 4824.5, -50.9,
        0.0020, -0.0134, 0.0127, -0.0023, 676.2, 0.9, 0.00045, 2361.4, 174.9,
        0.0015, 1427.5, -188.8, 0.0006, 2110.1, 153.6, 0.0014, 3606.8, -57.7,
        -0.0017, 2540.2, 121.7, -0.00099, 6704.8, -22.3, -0.0006, 5480.2, 24.5,
        0.00096, 1651.3, -118.3, 0.0006, 6310.8, -4.8, 0.0007, 4826.6, 36.2,
    ];
    private const SH = [
        -0.0009, 0.0037, 0.0, 0.0134, 1238.9, -16.4, -0.00426, 3040.9, -25.2,
        0.0064, 1835.3, 36.1, -0.0153, 610.8, -44.2, -0.0015, 2480.5, -69.4,
        -0.0014, 0.0026, 0.0, 0.0111, 1242.2, 78.3, -0.0045, 3034.96, 62.8,
        -0.0066, 1829.2, -51.5, -0.0078, 640.6, 24.2, -0.0016, 2363.4, -141.4,
        0.0006, -0.0002, 0.0, -0.0005, 1251.1, 43.7, 0.0005, 622.8, 13.7,
        0.0003, 1824.7, -71.1, 0.0001, 2997.1, 78.2,
    ];
    private const UH = [
        -0.0021, -0.0159, 0.0, 0.0299, 422.3, -17.7, -0.0049, 3035.1, -31.3,
        -0.0038, 945.3, 60.1, -0.0023, 1227.0, -4.99, 0.0134, -0.02186, 0.0,
        0.0317, 404.3, 81.9, -0.00495, 3037.9, 57.3, 0.004, 993.5, -54.4,
        -0.0018, 1249.4, 79.2, -0.0003, 0.0005, 0.0, 0.0005, 352.5, -54.99,
        0.0001, 3027.5, 54.2, -0.0001, 1150.3, -88.0,
    ];
    private const NH = [
        0.1832, -0.6718, 0.2726, -0.1923, 175.7, 31.8, 0.0122, 542.1, 189.6,
        0.0027, 1219.4, 178.1, -0.00496, 3035.6, -31.3, -0.1122, 0.166, -0.0544,
        -0.00496, 3035.3, 58.7, 0.0961, 177.1, -68.8, -0.0073, 630.9, 51.0,
        -0.0025, 1236.6, 78.0, 0.00196, -0.0119, 0.0111, 0.0001, 3049.3, 44.2,
        -0.0002, 893.9, 48.5, 0.00007, 1416.5, -0.252,
    ];
    private const PH = [
        -0.0426, 0.073, -0.029, 0.0371, 372.0, -331.3, -0.0049, 3049.6, -39.2,
        -0.0108, 566.2, 318.3, 0.0003, 1746.5, -238.3, -0.0603, 0.5002, -0.6126,
        0.049, 273.97, 89.97, -0.0049, 3030.6, 61.3, 0.0027, 1075.3, -28.1,
        -0.0007, 1402.3, 20.3, 0.0145, -0.0928, 0.1195, 0.0117, 302.6, -77.3,
        0.00198, 528.1, 48.6, -0.0002, 1000.4, -46.1,
    ];

    /** @var Planet[] */
    public array $planets = [];

    // ------------------------------------------------------------------

    public function __construct(int $m, int $d, int $y, float $gmt)
    {
        $jd    = new JulDate($m, $d, $y, $gmt);
        $ci    = $jd->ci;
        $ci2   = $ci * $ci;
        $ob    = deg2rad(23.452294 - 0.0130125 * $ci);
        $coord = new Coordinates();

        // Number of harmonic terms per outer planet (Jupiter…Pluto)
        $noTerms = [11, 5, 4, 4, 4];

        $harmTabs = [
            self::JH,
            self::SH,
            self::UH,
            self::NH,
            self::PH,
        ];

        // Heliocentric reference position of the Sun (planet 0)
        $xa = $ya = 0.0;
        // Geocentric reference position of the Sun (planet 0)
        $x1 = $y1 = $z1 = 0.0;

        for ($i = 0; $i < 9; $i++) {
            $this->planets[$i] = new Planet($i);

            // --- Kepler equation ---
            $ma  = deg2rad(Convert::Mod360(self::MA[$i][0] + self::MA[$i][1] * $ci + self::MA[$i][2] * $ci2));
            $ecc = self::ECC[$i][0] + self::ECC[$i][1] * $ci + self::ECC[$i][2] * $ci2;
            $ea  = $ma;
            for ($j = 0; $j < 6; $j++) {
                $ea = $ma + $ecc * sin($ea);
            }

            $e1 = 0.01720209 / (pow(self::AU[$i], 1.5) * (1.0 - $ecc * cos($ea)));
            $xw = -(self::AU[$i] * $e1) * sin($ea);
            $yw =  (self::AU[$i] * $e1) * sqrt(1.0 - $ecc * $ecc) * cos($ea);

            $ap      = deg2rad(self::AOP[$i][0]  + self::AOP[$i][1]  * $ci + self::AOP[$i][2]  * $ci2);
            $ascNode = deg2rad(self::AN[$i][0]   + self::AN[$i][1]   * $ci + self::AN[$i][2]   * $ci2);
            $in      = deg2rad(self::INCL[$i][0] + self::INCL[$i][1] * $ci + self::INCL[$i][2] * $ci2);

            // --- Rotate velocity vector into ecliptic frame (for $xh/$yh) ---
            $coord->RecToPol($xw, $yw);
            $coord->PolToRec($coord->a + $ap, $coord->r);

            $dx = $coord->x;
            $coord->RecToPol($coord->y, 0.0);
            $coord->PolToRec($coord->a + $in, $coord->r);

            $gz = $coord->y;
            $coord->RecToPol($dx, $coord->x);
            $a = $coord->a + $ascNode;
            if ($a < 0) { $a += 2.0 * M_PI; }
            $coord->PolToRec($a, $coord->r);

            $xh = $coord->x;
            $yh = $coord->y;

            if ($i === 0) {
                $xa = -$xh;
                $ya = -$yh;
            } else {
                $xw = $xh + $xa;
                $yw = $yh + $ya;
            }

            // --- Rotate position vector into ecliptic frame ---
            $x = self::AU[$i] * (cos($ea) - $ecc);
            $y = self::AU[$i] * sin($ea) * sqrt(1.0 - $ecc * $ecc);

            $coord->RecToPol($x, $y);
            $coord->PolToRec($coord->a + $ap, $coord->r);

            $dx = $coord->x;
            $coord->RecToPol($coord->y, 0.0);
            $coord->PolToRec($coord->a + $in, $coord->r);

            $gz = $coord->y;
            $coord->RecToPol($dx, $coord->x);
            $a = $coord->a + $ascNode;
            if ($a < 0) { $a += 2.0 * M_PI; }
            $coord->PolToRec($a, $coord->r);

            $xx = $coord->x;
            $yy = $coord->y;
            $zz = $gz;

            // --- Apply outer-planet harmonic perturbations ---
            if ($i > 3) {
                $tab    = $harmTabs[$i - 4];
                $nTerms = $noTerms[$i - 4];
                $tx     = [0.0, 0.0, 0.0];

                for ($ik = 0; $ik < 3; $ik++) {
                    if ($i === 4 && $ik === 2) {
                        $tx[2] = 0.0;
                        continue;
                    }
                    $localN = ($ik === 2) ? $nTerms - 1 : $nTerms;
                    $idx    = 0;
                    $s      = deg2rad($tab[$idx] + $tab[$idx + 1] * $ci + $tab[$idx + 2] * $ci2);
                    $idx   += 3;
                    $sum    = 0.0;
                    for ($ij = 0; $ij < $localN; $ij++) {
                        $u1   = $tab[$idx++];
                        $v    = $tab[$idx++];
                        $w    = $tab[$idx++];
                        $sum += deg2rad($u1) * cos(($v * $ci + $w) * M_PI / 180.0);
                    }
                    $tx[$ik] = rad2deg($s + $sum);
                }

                $xx += $tx[1];
                $yy += $tx[0];
                $zz += $tx[2];
            }

            // --- Geocentric conversion + retrograde detection ---
            $coord->RecToPol($xx, $yy);
            $c = Convert::Mod360(rad2deg($coord->a));

            $xk = ($xx * $yh - $yy * $xh) / ($xx * $xx + $yy * $yy);

            if ($i === 0) {
                $x1 = $xx;
                $y1 = $yy;
                $z1 = $zz;
            } else {
                $xx -= $x1;
                $yy -= $y1;
                $zz -= $z1;
                $xk  = ($xx * $yw - $yy * $xw) / ($xx * $xx + $yy * $yy);
            }

            $br = 0.0057756 * sqrt($xx * $xx + $yy * $yy + $zz * $zz) * rad2deg($xk);

            $this->planets[$i]->rx = ($xk < 0) ? 'Rx' : ' ';

            $coord->RecToPol($xx, $yy);
            $c = rad2deg($coord->a) - $br;
            $c = ($i === 0) ? Convert::Mod360($c + 180.0) : Convert::Mod360($c);

            $coord->RecToPol($coord->r, $zz);
            $a = $coord->a;
            if ($a > 0.35) { $a -= 2.0 * M_PI; }

            $this->planets[$i]->long = $c;
            $this->planets[$i]->lat  = rad2deg($a);

            $this->calcDeclin($i, $ob);
        }

        $this->calcMoon($jd, $gmt);

        // Re-order so final array is: Sun(0), Moon(1), Mercury(2) … Pluto(9), NN(10), SN(11), MN(12)
        $this->planets[9]->id = 2;
        $moon = $this->planets[9];
        array_splice($this->planets, 9, 1);
        array_splice($this->planets, 1, 0, [$moon]);
    }

    // ------------------------------------------------------------------
    // Moon & nodes
    // ------------------------------------------------------------------

    private function calcMoon(JulDate $jd, float $gmt): void
    {

		// note:
		// moon = $this->planets[9]
		// north node = $this->planet[10]
		// south node = $this->planet[11]
		// mean_node = $this->planets[12]

		for ($i=9;$i<13;$i++)
		{
			$this->planets[$i] = new Planet($i);
		}

		$m = 3600.0;
		$ci = $jd->ci;
		$ob = deg2rad(23.452294 - .0130125 * $ci);
		$ci2 = $ci*$ci;
	
		$ll = 973563.0 + 1732564379.0 * $ci - 4.0 * $ci2;
		$g = 1012395.0 + 6189.0 * $ci;
		$n = 933060.0 - 6962911.0 * $ci + 7.5 * $ci2;


	//Mean Node		
		$this->planets[12]->long = fmod($n/$m, 360);
	
		$g1 = 1203586.0 + 14648523.0 * $ci - 37.0 * $ci2;
		$d = 1262655.0 + 1602961611.0 * $ci - 5.0 * $ci2;

		$l = ($ll - $g1) / $m;
		$l1 = (($ll - $d) - $g) / $m;
		$f = ($ll - $n) / $m;
		$d /= $m;
		$y = 2.0 * $d;

	//  Compute Moon's Perturbations
		$ml = 22639.6  * sin(deg2rad($l)) - 4586.4 * sin(deg2rad($l - $y));
		$ml = $ml + 2369.9 * sin(deg2rad($y)) + 769.0 * sin(deg2rad(2.0 * $l)) - 669.0 * sin(deg2rad($l1));
		$ml = $ml - 411.6 * sin(deg2rad(2 * $f)) - 212.0 * sin(deg2rad( 2.0 * $l - $y));
		$ml = $ml - 206.0 * sin(deg2rad($l + $l1 - $y)) + 192.0 * sin(deg2rad($l + $y));
		$ml = $ml - 165.0 * sin(deg2rad($l1 - $y)) + 148.0 * sin(deg2rad($l - $l1)) - 125.0 * sin(deg2rad($d));
		$ml = $ml - 110.0 * sin(deg2rad($l + $l1)) - 55.0 * sin(deg2rad(2.0 * $f - $y));
		$ml = $ml - 45.0 * sin(deg2rad($l + 2.0 * $f)) + 40.0 * sin(deg2rad($l - 2.0 * $f));

		$this->planets[9]->long = Convert::Mod360(($ll + $ml) / $m);


	//Moon's Latitude
		$mb = 18461.5 * sin(deg2rad($f)) + 1010.0 * sin(deg2rad($l + $f)) - 999.0 * sin(deg2rad($f - $l));
		$mb = $mb - 624.0 * sin(deg2rad($f - $y)) + 199.0 * sin(deg2rad($f + $y - $l));
		$mb = $mb - 167.0 * sin(deg2rad($l + $f - $y)) + 117.0 * sin(deg2rad($f + $y));
		$mb = $mb + 62.0 * sin(deg2rad(2.0 * $l + $f)) - 33.0 * sin(deg2rad($f - $y - $l));
		$mb = $mb - 32.0 * sin(deg2rad($f - 2.0 * $l)) - 30.0 * sin(deg2rad($l1 + $f - $y));
		$this->planets[9]->lat = fmod($mb/$m,360);
		$this->planets[9]->rx = ' ';

	//True North Node	
		$tn = $n + 5392.0 * sin(deg2rad(2 * $f - $y)) - 541.0 * sin(deg2rad($l1)) - 442.0 * sin(deg2rad($y));
		$tn = $tn + 423.0 * sin(deg2rad(2.0 * $f)) - 291.0 * sin(deg2rad(2.0 * $l - 2.0 * $f));

		$this->planets[10]->long = Convert::Mod360($tn / $m);
	
		if ($this->planets[10]->long < 0)
		{
			$this->planets[10]->long = abs($this->planets[10]->long);
			$this->planets[10]->rx = 'R';
		}
		else
		{
			$this->planets[10]->rx = ' ';
		}
	
		$this->planets[10]->lat = 0.0;
		$this->planets[10]->dcl = 0.0;

    //south node

		$this->planets[11]->long = Convert::Mod360($this->planets[10]->long+180);
		$this->planets[11]->rx = $this->planets[10]->rx;
		$this->planets[11]->lat = 0.0;
	
		$this->planets[12]->long = abs($this->planets[12]->long);

		$this->planets[12]->lat = 0.0;
		$this->planets[12]->dcl = 0.0;
		$this->planets[12]->rx = ' ';

		for($p=9;$p<13;$p++){
			$this->calcDeclin($p, $ob);
		}
        
    }

    // ------------------------------------------------------------------
    // Declination
    // ------------------------------------------------------------------

    private function calcDeclin(int $planetIdx, float $ob): float
    {
        $p     = $this->planets[$planetIdx];
        $coord = new Coordinates();

        $coord->PolToRec(deg2rad($p->lat), 1.0);
        $q = $coord->y;
        $r = $coord->x;

        $coord->PolToRec(deg2rad($p->long), $r);
        $coord->RecToPol($coord->y, $q);

        $coord->PolToRec($coord->a + $ob, $coord->r);
        $p->dcl = rad2deg(asin($coord->y));

        return $p->dcl;
    }

    // ------------------------------------------------------------------
    // Utilities
    // ------------------------------------------------------------------

    /**
     * Return true if the Moon will make no major aspect before leaving
     * its current sign (void-of-course).
     */
    public function moonVoidOfCourse(): bool
    {
        $moonSign    = (int) ($this->planets[1]->long / 30.0) * 30;
        $moonDeg     = $this->planets[1]->long - $moonSign;

        for ($i = 0; $i < 9; $i++) {
            if ($i === 1) { continue; } // skip Moon itself
            $sign      = (int) ($this->planets[$i]->long / 30.0) * 30;
            $planetDeg = $this->planets[$i]->long - $sign;
            if ($planetDeg >= $moonDeg) {
                return false;
            }
        }

        return true;
    }
}