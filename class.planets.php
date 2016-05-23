<?php
require_once("cnvt.php");
require_once ("class.juldate.php");
require_once("class.planet.php");

class Planets {
	public $ma = array (
		array(358.47584 , 35999.0498 ,-.00015) ,
		array(102.27938 , 149472.515 , 0.0) ,
		array(212.6032 , 58517.8039 , .0013),
		array(319.5293 , 19139.8585 , .00018) ,
		array(225.4928, 3033.6879,0.0),
		array(174.2153,1223.50796,0),
		array(74.1757 , 427.2742, 0.0),
		array(30.13294, 240.45516, 0.0),
		array(229.781, 145.1781, 0.0)
	);
		
	public $ecc = array (
		array(.016751 ,-.000041 , 0.0) ,
		array(.205614 , .00002 , 0.0) ,
		array(.00682 ,-.00005 , 0.0) ,
		array(.09331 , .00009 , 0.0) ,
		array(.04838, .00002, 0.0) ,
		array(.05423 ,-.0002 , 0.0) ,
		array(.04682 ,.00042 , 0.0) ,
		array(.00913, -.00127 , 0.0),
		array(.24797, 0.002898, 0.0)
	);

	public $au = array ( 1.00000013, .387098, .7233, 1.52369, 5.2029, 9.5525, 19.2215, 30.11375, 39.539);
	
	public $aop = array(
		array( 101.22083 , 1.71918 , .00045) ,
		array( 28.75375 , .37028 , .00012) ,
		array( 54.3842, .5082 ,-.0014),
		array( 285.43176 , 1.06977 , .00013) ,
		array( 273.393, 1.3383, 0.0),
		array( 338.9117,-.3167,0.0) ,
		array( 95.6863, 2.0508, 0.0),
		array( 284.1683, -21.6329, 0.0),
		array( 113.5366, 0.2086, 0.0)
	);

	public $an = array(
		array( 0.0 , 0.0 , 0.0) ,
		array( 47.14594 , 1.1852 , .00017) ,
		array( 75.7796, .8999, .0004),
		array( 48.78644 , .77099 , 0.0) ,
		array( 99.4198, 1.0583, 0.0),
		array( 112.8261, .8259 ,0.0),
		array( 73.5222, .5242, 0.0),
		array( 130.68415, 1.1005, 0.0),
		array( 108.944, 1.3739, 0.0)
	);

	public $incl = array(
		array( 0.0 , 0.0 , 0.00) ,
		array( 7.00288 , .00186 ,-.00001) ,
		array( 3.3936, .001 , 0.0) ,
		array( 1.85033 ,-.00068 , .0001) ,
		array( 1.3097 ,-.0052 , 0.0) ,
		array( 2.4908 ,-.0047 ,0.0),
		array( .7726, .0001, 0.0),
		array( 1.7794,-.0098, 0.0) ,
		array( 17.1514, -0.0161, 0.0)
	);

	public $jh = array ( -.001,-.0005,.0045,.0051,581.7,-9.7,-.0005,2510.7,-12.5,-.0026,1313.7,
		-61.4,.0013,2370.79,-24.6,-.0013,3599.3,37.7,-.001,2574.7,31.4,-.00096,6708.2,-114.5,
		-.0006,5499.4,-74.97,-.0013,1419,54.2,.0006,6339.3,-109,.0007,4824.5,-50.9,.0020,-.0134,
		.0127,-.0023,676.2,.9,.00045,2361.4,174.9,.0015,1427.5,-188.8,.0006,2110.1,153.6,.0014,3606.8,
		-57.7,-.0017,2540.2,121.7,-.00099,6704.8,-22.3,-.0006,5480.2,24.5,.00096,1651.3,-118.3,.0006,
		6310.8,-4.8,.0007,4826.6,36.2);

	public $sh= array ( -.0009,.0037,0,.0134,1238.9,-16.4,-.00426,3040.9,-25.2,.0064,
		1835.3,36.1,-.0153,610.8,-44.2,-.0015,2480.5,-69.4,-.0014,.0026,0,.0111,
		1242.2,78.3,-.0045,3034.96,62.8,-.0066,1829.2,-51.5,-.0078,640.6,24.2,
		-.0016,2363.4,-141.4,.0006,-.0002,0,-.0005,1251.1,43.7,.0005,622.8,13.7,
		.0003,1824.7,-71.1,.0001,2997.1,78.2);

	public $uh= array ( -.0021, -.0159, 0, .0299, 422.3, -17.7, -.0049, 3035.1, -31.3,
		-.0038, 945.3, 60.1, -.0023, 1227, -4.99, .0134, -.02186, 0.0, .0317,
		404.3 ,81.9, -.00495, 3037.9, 57.3, .004, 993.5, -54.4, -.0018, 1249.4,
		79.2, -.0003, .0005, 0.0, .0005, 352.5, -54.99, .0001, 3027.5, 54.2,
		-.0001, 1150.3, -88);

	public $nh= array ( .1832, -.6718, .2726, -.1923, 175.7, 31.8, .0122, 542.1, 189.6, .0027, 1219.4, 
		178.1, -.00496, 3035.6, -31.3, -.1122, .166, -.0544, -.00496, 3035.3, 58.7, .0961, 177.1, -68.8,
		-.0073, 630.9, 51.0, -.0025, 1236.6, 78.0, .00196, -.0119, .0111, .0001, 3049.3, 44.2, -.0002, 
		893.9, 48.5, .00007, 1416.5, -.252);

	public $ph= array ( -.0426, .073, -.029, .0371, 372.0, -331.3, -.0049, 3049.6,
		-39.2, -.0108, 566.2, 318.3, .0003, 1746.5, -238.3, -.0603, .5002, -0.6126,
		.049, 273.97, 89.97, -.0049, 3030.6, 61.3, .0027, 1075.3, -28.1, -.0007,
		1402.3, 20.3, .0145, -.0928, .1195, .0117, 302.6, -77.3, .00198, 528.1,
		48.6, -.0002, 1000.4, -46.1);	
		
	public $planets = array();

	function __construct($m, $d, $y, $gmt)
	{
		$jd = new JulDate($m, $d, $y, $gmt);
		$ci = $jd->ci;
		$coord = new Coordinates();
				
		$ob = deg2rad(23.452294 - .0130125 * $ci);
				
		$ci2 = $ci*$ci;
		$no_terms[0] = 11;
		$no_terms[1] = 5;
		
		for ($i=2; $i< 5; $i++)
			$no_terms[$i] = 4;
		for ($i=0; $i<3; $i++)
			$tx[$i] = 0.0;
		$harm_tab[0] = $this->jh;
		$harm_tab[1] = $this->sh;
		$harm_tab[2] = $this->uh;
		$harm_tab[3] = $this->nh;
		$harm_tab[4] = $this->ph;
		

		for ($i=0; $i<9; $i++)
		{
			$this->planets[$i] = new Planet($i);	
					
			$ea = $m = deg2rad(Mod360($this->ma[$i][0] + $this->ma[$i][1] * $ci + $this->ma[$i][2] * $ci2));
			
			$e = $this->ecc[$i][0] + $this->ecc[$i][1] * $ci + $this->ecc[$i][2] * $ci2;

			for ($j=1; $j<7; $j++)
	    		$ea = $m + $e * sin($ea);

			$e1 = .01720209 / (pow($this->au[$i],1.5) * (1 - $e * cos($ea)));
			$xw = -($this->au[$i] * $e1) * sin($ea);
			$yw = ($this->au[$i] * $e1) * sqrt(1 - $e * $e ) * cos($ea);
			$ap = deg2rad($this->aop[$i][0] + $this->aop[$i][1] * $ci + $this->aop[$i][2] * $ci2);
			$ascnode = deg2rad($this->an[$i][0] + $this->an[$i][1] * $ci + $this->an[$i][2] * $ci2);
			$in = deg2rad($this->incl[$i][0] + $this->incl[$i][1] * $ci + $this->incl[$i][2] * $ci2);

			$x = $xw;
			$y = $yw;
		
			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
			$a += $ap;
		
			$coord->PolToRec($a, $r);
			$x = $coord->x;
			$y = $coord->y;
			$d = $x;
			$x = $y;
			$y = 0;
			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
		
			$a += $in;
			$coord->PolToRec($a, $r);
			$x = $coord->x;
			$y = $coord->y;
		
			$g = $y;
			$y = $x;
			$x = $d;
			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
		
			$a += $ascnode;
			if ($a < 0)
				$a += (2 * M_PI);
			$coord->PolToRec($a, $r);
			$x = $coord->x;
			$y = $coord->y;

			$xh = $x;

			$yh = $y;

			if (!$i)
			{
				$xa = -$xh;
				$ya = -$yh;
			}
			else
			{
				$xw = $xh + $xa;
				$yw = $yh + $ya;
			}

			$x = $this->au[$i] * (cos($ea) - $e);
			$y = $this->au[$i] * sin($ea) * sqrt(1 - $e * $e);

			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
			$a += $ap;
		
			$coord->PolToRec($a, $r);
			$x = $coord->x;
			$y = $coord->y;
			$d = $x;
			$x = $y;
			$y = 0;
		
			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
			$a += $in;
			$coord->PolToRec($a, $r);
			$x = $coord->x;
			$y = $coord->y;
			$g = $y;
			$y = $x;
			$x = $d;
			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
			$a += $ascnode;
			if ($a < 0)
				$a += (2 * M_PI);
			$coord->PolToRec($a, $r);
			$x = $coord->x;
			$y = $coord->y;

			$xx = $x;
			$yy = $y;
			$zz = $g;			
			
/*  												*/
/*		get harmonics								*/
/*													*/
			if ($i > 3)
			{
				for ($ik=0; $ik<3; $ik++)
				{
					if (($i == 4) && ($ik == 2))
						$tx[2] = 0;
					else
					{
						if ($ik == 2)
							$no_terms[$i-4]--;
						$h_ptr = $harm_tab[$i-4];
						$idx = 0;
						$s = deg2rad($h_ptr[$idx] + $h_ptr[$idx+1] * $ci + $h_ptr[$idx+2] * $ci2);
//						echo "<p>" . $h_ptr[$idx] . ', ' . $harm_tab[$i-4][$idx] . "</p>";
						$idx += 3;

						$a = 0;
						for ($ij = 0;$ij < $no_terms[$i-4];$ij++)
						{
							$u1 = $h_ptr[$idx++];
							$v = $h_ptr[$idx++];
							$w = $h_ptr[$idx++];

							$a = $a + deg2rad($u1) * cos(($v * $ci + $w) * M_PI/180);
						}
						$tx[$ik] = rad2deg($s + $a);
					}
				}
/*  												*/
/*		end of get harmonics						*/
/*													*/

		  	  	$xx = $xx + $tx[1];
				$yy = $yy + $tx[0];
				$zz = $zz + $tx[2];
			}
			
			$xk = ($xx * $yh - $yy * $xh) / ($xx * $xx + $yy * $yy);
			$br = 0;
			$x = $xx;
			$y = $yy;
						
			$coord->RecToPol($x, $y);
			$a = $coord->a;
			$r = $coord->r;
			$c = rad2deg($a);
			$c = Mod360($c);
			$y = $zz;
			$x = $r;
			$coord->RecToPol($x, $y);
			
			$a = $coord->a;
			$r = $coord->r;
			if ($a > .35)
				$a -= (2 * M_PI);

			if (!$i)
			{
				$x1 = $xx;
				$y1 = $yy;
				$z1 = $zz;
			}
			else
			{
				$xx = $xx - $x1;
				$yy = $yy - $y1;
				$zz = $zz - $z1;
				$xk = ($xx * $yw - $yy * $xw) / ($xx * $xx + $yy * $yy);
			}
						
			$br = .0057756 * sqrt($xx * $xx + $yy * $yy + $zz * $zz) * rad2deg($xk);
			
			if ($xk < 0)
			{
				$this->planets[$i]->rx = 'Rx';
			}
			else
			{
				$this->planets[$i]->rx= ' ';
			}
			$x = $xx;
			$y = $yy;
			$coord->RecToPol($x, $y);
			
			$a = $coord->a;
			$r = $coord->r;
			$c = rad2deg($a) - $br;
			if (!$i)
				$c = Mod360($c+180);
			else
				$c = Mod360($c);
			$y = $zz;
			$x = $r;
			$coord->RecToPol($x, $y);
			
			$a = $coord->a;
			$r = $coord->r;
			if ($a > .35)
				$a -= (2 * M_PI);
				
			$this->planets[$i]->long = $c;
			$this->planets[$i]->lat = rad2deg($a);
			
			$this->Declin($i, $ob);			
		}

		$this->CalcMoon($jd, $gmt);
	}

	function CalcMoon( $jd, $gmt)
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

		$this->planets[9]->long = Mod360(($ll + $ml) / $m);

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

		$this->planets[10]->long = Mod360($tn / $m);
	
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
		$this->planets[11]->long = Mod360($this->planets[10]->long+180);
		$this->planets[11]->rx = Mod360($this->planets[10]->rx);
		$this->planets[11]->lat = 0.0;
	
		$this->planets[12]->long = abs($this->planets[12]->long);

		$this->planets[12]->lat = 0.0;
		$this->planets[12]->dcl = 0.0;
		$this->planets[12]->rx = ' ';
	
		$this->Declin(9, $ob);
		$this->Declin(10, $ob);
		$this->Declin(11, $ob);
		$this->Declin(12, $ob);
	}

	function Declin($planet, $ob)
	{
		$p = $this->planets[$planet];
		
		$coord = new Coordinates();
		$a = deg2rad($p->lat);
		$r = 1;
		$coord->PolToRec($a, $r);
		$q = $coord->y;
		$r = $coord->x;
		$a = deg2rad($p->long);
		$coord->PolToRec($a, $r);
		$x = $coord->y;
		$y = $q;
		$coord->RecToPol($x, $y);
		$a = $coord->a + $ob;
		$coord->PolToRec($a, $coord->r);
		$p->dcl = rad2deg(asin($coord->y));
		return $p->dcl;
	}
	
	function MoonVoidOfCourse()
	{
		$voc = true;
		$s = (int) ($this->planets[9]->long / 30.0) * 30;
		$moon_deg = (int) $this->planets[9]->long - $s;
		$moon_min = (int) ((($this->planets[9]->long - $s - $moon_deg) * 60.0) + .5);
		$moon_deg = $moon_deg + ($moon_min / 60);
		
		for ($i=0;$i<9;$i++)
		{
			$s = (int) ($this->planets[$i]->long / 30.0) * 30;
			$planet_deg = (int) $this->planets[$i]->long - $s;
			$planet_min = (int) ((($this->planets[$i]->long - $s - $planet_deg) * 60.0) + .5);
			
			$planet_deg = $planet_deg + ($planet_min / 60);
			
			if ($planet_deg >= $moon_deg)
				$voc = false;
		}
		return $voc;
	}
}
