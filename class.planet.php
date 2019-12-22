<?php

class Planet {
	public $id;
	public $long;
	public $lat;
	public $dcl;
	public $rx;
	public $longName;
	public $shortName;
	public $glyph;
	
	function __construct($id)
	{
		$longName = array('Sun', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'Moon', 'North Node', 'South Node', 'Mean Node');
		$shortName = array('SU', 'ME', 'VE', 'MA', 'JU', 'SA', 'UR', 'NE', 'PL', 'MO', 'NN', 'SN', 'MN');
		$glyph = array ('A', 'C', 'D','E','F','G','H','I','K', 'B', 'L', 'M', 'L');
		$this->id = $id;
		$this->rx = '';
		$this->longName = $longName[$id];
		$this->shortName = $shortName[$id];	
		$this->glyph = $glyph[$id];
	}
};