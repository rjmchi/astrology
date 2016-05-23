<?php
require_once('cnvt.php');
require_once('class.juldate.php');

class Houses {

	public $asc;
	public $mc;
	public $vx;
	public $ep;
	public $ci;
	public $ob;
	public $ra;
	public $lat;
	public $lng;
	public $gmt;
	public $house = array();
	
	function __construct($mm, $dd, $yyyy, $gmt, $lat, $lng)
	{
		$jd = new JulDate($mm, $dd, $yyyy, $gmt);
		$this->lat = deg2rad($lat);
		$this->lng = deg2rad($lng);
		$this->ci = $jd->ci;
		$this->gmt = $gmt;
		$this->ob = deg2rad(23.452294 - .0130125 * $this->ci);
		$this->ra = deg2rad(Mod360((6.6460656 + 2400.0513 * $this->ci + 2.58e-5 * $this->ci * $this->ci + $gmt) * 15 - $lng));
		$this->asc = $this->asc();
		$this->mc  = $this->mc();
		$this->vx = $this->vx();
		$this->ep = $this->ep();
	}
	
	function asc()
	{
		$b=atan(cos($this->ra) / (-sin($this->ra) * cos($this->ob) - tan($this->lat) * sin($this->ob)));
		if ($b < 0.0)
			$b += M_PI;
		if (cos($this->ra) < 0.0)
			$b += M_PI;
		return (Mod360(rad2deg($b)));
}

	function mc()
	{
		$m = atan(tan($this->ra) / cos($this->ob));
		if ($m < 0.0)
			$m += M_PI;
		if ($this->ra > M_PI)
			$m += M_PI;
		return (Mod360(rad2deg($m)));
}

	function vx()
	{
		$x = cos($this->ra + M_PI);

		$y = -sin($this->ra + M_PI) * cos($this->ob) - sin($this->ob) / tan($this->lat);
		$v = atan($x / $y);
		if ($v < 0.0)
			$v += M_PI;
		if ($x < 0.0)
			$v += M_PI;
		return (rad2deg($v));
	}

	function ep()
	{
		if ($this->ra)
			$e = atan(cos($this->ra) / (-sin($this->ra) * cos($this->ob)));
		else
			$e = 0;
			
		if ($e < 0.0)
			$e += M_PI;
		if (cos($this->ra) < 0.0)
			$e += M_PI;
		return rad2deg($e);
	}
	
	function PlacHouses()
	{
		$this->house[0] = $this->asc;
		$this->house[6] = Mod360($this->house[0] + 180);

		$this->house[9] = $this->mc;		
		$this->house[3] = Mod360($this->house[9] + 180);

		$this->house[1] = $this->plac_house(2);
		$this->house[7] = Mod360($this->house[1] + 180);
		
		$this->house[2] = $this->plac_house(3);
		$this->house[8] = Mod360($this->house[2] + 180);
		
		$this->house[10] = $this->plac_house(11);
		$this->house[4] = Mod360($this->house[10] + 180);
		
		$this->house[11] = $this->plac_house(12);
		$this->house[5] = Mod360($this->house[11] + 180);
	}

	function plac_house($h)
	{
		switch($h)
		{
			case 2:
				$ff = 1.5;
				$k = 1;
				break;
			case 3:
				$ff = 3.0;
				$k = 1;
				break;
			case 11:
				$ff = 3.0;
				$k = -1;
				break;
			case 12:
				$ff = 1.5;
				$k = -1;
				break;
		}
		
		$r1 = $this->ra;
		for ($i=1;$i<=10;$i++)
		{
			$xx=acos($k * sin($r1) * tan($this->ob) * tan($this->lat));
			if ($xx < 0)
				$xx+=M_PI;
			$r2=$this->ra+($xx/$ff);
			if ($k==1)
				$r2 = $this->ra + M_PI - ($xx/$ff);
			$r1=$r2;
		}
		$lo = atan(tan($r1)/cos($this->ob));
		if ($lo < 0)
			$lo+=M_PI;
		if (sin($r1)<0)
			$lo+=M_PI;
		return (rad2deg($lo));
	}
	function KochHouses()
	{
		$a1 = asin(sin($this->ra)*tan($this->lat)*tan($this->ob));
		for ($i = 1;$i<7;$i++)
		{
			$d = Mod360(60 + 30 * $i);
			$a2 = $d / 90 - 1;
			$kn = 1;
			if ($d >= 180)
			{
				$kn = -1;
				$a2 = $d / 90 - 3;
			}
			$a3 = deg2rad(Mod360(rad2deg($this->ra)+$d+$a2*rad2deg($a1)));
			$x = atan(sin($a3) / (cos($a3) * cos($this->ob) - $kn * tan($this->lat) * sin($this->ob)));
			if ($x < 0)
				$x += M_PI;
			if (sin($a3) < 0)
				$x  += M_PI;
			$this->house[$i-1] = Mod360(rad2deg($x));
			$this->house[$i+5] = Mod360(rad2deg($x)+180);
		}
	}	
	function EqualHouses()
	{
		$this->house[0] = $this->asc();
		for ($i=1;$i<12;$i++)
			$this->house[$i] = Mod360($this->house[$i-1]+30.0);
	}	
	function RegiomontanusHouses()
	{
		for ($i=1;$i<7;$i++)
		{
			$d = deg2rad(60 + 30 * $i);
			$y = sin($this->ra+$d);
			$x = atan($y/(cos($this->ra + $d) * cos($this->ob) - sin($d) * tan($this->lat) * sin($this->ob)));
			if ($x < 0)
				$x += M_PI;
			if ($y < 0)
				$x += M_PI;
			$this->house[$i-1] = Mod360(rad2deg($x));
			$this->house[$i+5] = Mod360(rad2deg($x)+180);
		}
	}	
	function PorphyryHouses()
	{
		$x = $this->asc - $this->mc;
		if ($x < 0)
			$x+=360;
		$y = $x / 3;
		for ($i=1;$i<3;$i++)
		{
			$this->house[$i+3] = Mod360(180+$this->mc+$i*$y);
		}
		$x = Mod360(180+$this->mc)-$this->asc;
		if ($x < 0)
			$x += 360;
		$this->house[0] = $this->asc;
		$y = $x/3;
		for ($i=1;$i<4;$i++)
			$this->house[$i] = Mod360($this->asc+$i*$y);
		for ($i=0;$i<6;$i++)
			$this->house[$i+6] = Mod360($this->house[$i]+180.0);
	}	
	
	function MeridianHouses()
	{
		for ($i=1;$i<7;$i++)
		{
			$d = deg2rad(60 + 30 * $i);
			$y = sin($this->ra+$d);
			$x = atan($y/(cos($this->ra +$d) * cos($this->ob)));
			if ($x < 0)
				$x += M_PI;
			if ($y < 0)
				$x += M_PI;
			$this->house[$i-1] = Mod360(rad2deg($x));
			$this->house[$i+5] = Mod360(rad2deg($x)+180);
		}
	}	
	function MorinusHouses()
	{
		for ($i=1;$i<7;$i++)
		{
			$d = deg2rad(60 + 30 * $i);
			$y = sin($this->ra+$d) * cos($this->ob);
			$x = atan($y/cos($this->ra + $d));
			if ($x < 0)
				$x += M_PI;
			if ($y < 0)
				$x += M_PI;
			$this->house[$i-1] = Mod360(rad2deg($x));
			$this->house[$i+5] = Mod360(rad2deg($x)+180);
		}
	}
	function CampanusHouses()
	{
		for ($i=1;$i<7;$i++)
		{
			$ko = deg2rad(60.000001 + 30.0 * $i);
			$dn = atan(tan($ko) * cos($this->lat));
			if ($dn < 0)
				$dn = 3;
			$dn = atan(tan($ko) * cos($this->lat));
			if ($dn < 0)
				$dn += M_PI;
			if (sin($ko) < 0)
				$dn += M_PI;
			$y = sin($this->ra+$dn);
			$x = cos($this->ra+$dn) * cos($this->ob) - sin($dn) * tan($this->lat) * sin($this->ob);
			$x = atan($y/$x);
			if ($x < 0)
				$x+= M_PI;
			if ($y < 0)
				$x += M_PI;

			$this->house[$i-1] = Mod360(rad2deg($x));
			$this->house[$i+5] = Mod360(rad2deg($x)+180);
		}
	}	
	function TopocentricHouses()
	{
		$rad30 = deg2rad(30);
		$this->house[9] = $this->mc;
		$this->house[3] = Mod360($this->house[9]+180.0);

		$p1 = atan(tan($this->lat) / 3.0);
		$p2 = atan((tan($this->lat) / 3.0) * 2.0);
		$ra = Mod2Pi($this->ra + $rad30 );
		$this->house[10] = $this->TopHouse($p1,$ra);
		$this->house[4] = Mod360($this->house[10]+180.0);

		$ra = Mod2Pi($ra+$rad30 );
		$this->house[11] = $this->TopHouse($p2, $ra);
		$this->house[5] = Mod360($this->house[11]+180.0);

		$ra = Mod360($ra+$rad30 );
		$this->house[0] = $this->TopHouse($this->lat, $ra);
		$this->house[6] = Mod360($this->house[0]+180.0);

		$ra = Mod360($ra+$rad30 );
		$this->house[1] = $this->TopHouse($p2, $ra);
		$this->house[7] = Mod360($this->house[1]+180.0);

		$ra = Mod360($ra+$rad30 );
		$this->house[2] = $this->TopHouse($p1, $ra);
		$this->house[8] = Mod360($this->house[2]+180.0);
	}

	function TopHouse($lat, $ra)
	{
		$x = atan(tan($lat)/cos($ra));
		$y = $x + $this->ob;
		$h=atan(cos($x) * tan($ra) / cos($y));
		if ($h < 0)
			$h=$h+M_PI;
		if (sin($ra) < 0)
			$h = $h+M_PI;
		return Mod360(rad2deg($h));
	}
}