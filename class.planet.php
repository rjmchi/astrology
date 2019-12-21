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
		$longName = array('Sun', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'Moon', 'North Node', 'South Node', 'Mean Node');
		$shortName = array('SU', 'ME', 'VE', 'MA', 'JU', 'SA', 'UR', 'NE', 'PL', 'MO', 'NN', 'SN', 'MN');
		$this->id = $id;
		$this->rx = '';
		$this->longName = $longName[$id];
		$this->shortName = $shortName[$id];			
	}
};