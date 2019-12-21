<?php

class Coordinates {
	public $a;
	public $r;
	public $x;
	public $y;
	
	function RecToPol($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
		$c = Convert::RecToPol($x, $y);

		$this->a = $c['a'];
		$this->r = $c['r'];
	}

	function PolToRec($a, $r)
	{
		$this->a = $a;
		$this->r = $r;
		$c = Convert::PolToRec($a,$r);
		$this->x = $c['x'];
		$this->y = $c['y'];	
	}
}