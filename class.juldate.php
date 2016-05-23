<?php
class JulDate {
	public $jd;
	public $ci;
	
	function __construct($m, $d, $y, $gmt)
	{
		$im = 12*($y+4800)+$m-3;
		$j = (2*($im%12)+7+365*$im)/12;
		$j = (int)$j+$d+(int)($im/48)-32083;
		
		if ($j > 2299171)
		{
			$this->jd = $j+(int)($im/4800)-(int)($im/1200)+38;
		}
		else
		{
			$this->jd = $j;
		}
		$this->ci = ((($this->jd-2415020.0)+$gmt /24.0-.5)/36525.0);				
	}

	function getString()
	{
		$l = (int)($this->jd+.5)+68569;
		$n = (int) (4*$l/146097);
		$l = $l- (int) ((146097 * $n + 3)/4);
		$it = (int) (4000 * ($l+1)/1461001);
		$l = $l - (int)(1461 * $it / 4) + 31;
		$month = (int) (80 * $l / 2447);
		$day = $l - (int) (2447 * $month / 80);
		$l = (int) ($month/11);
		$month = $month+2 - 12 * $l;
		$year = 100 * ($n-49)+$it+$l;
		$cs = sprintf("%d/%d/%d", $month, $day, $year);
		return $cs;		
	}
}
