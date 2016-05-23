<?php

class Planet {
	public $id;
	public $long;
	public $lat;
	public $dcl;
	public $rx;
	public $longName;
	public $shortName;
	
	function __construct($id)
	{
		$this->id = $id;
		$this->rx = '';
		switch ($id)
		{
			case 0:
				$this->shortName = "SU";
				$this->longName = "Sun";
				break;
			case 1:
				$this->shortName = "ME";
				$this->longName = "Mercury";
				break;
			case 2:
				$this->shortName = "VE";
				$this->longName = "Venus";
				break;
			case 3:
				$this->shortName = "MA";
				$this->longName = "Mars";
				break;
			case 4:
				$this->shortName = "JU";
				$this->longName = "Jupiter";
				break;
			case 5:
				$this->shortName = "SA";
				$this->longName = "Saturn";
				break;
			case 6:
				$this->shortName = "UR";
				$this->longName = "Uranus";
				break;
			case 7:
				$this->shortName = "NE";
				$this->longName = "Neptune";
				break;
			case 8:
				$this->shortName = "PL";
				$this->longName = "Pluto";
				break;
			case 9:
				$this->shortName = "MO";
				$this->longName = "Moon";
				break;
			case 10:
				$this->shortName = "NN";
				$this->longName = "North Node";
				break;
			case 11:
				$this->shortName = "SN";
				$this->longName = "South Node";
				break;
			case 12:
				$this->shortName = "MN";
				$this->longName = "Mean Node";
				break;
		}		
	}
};