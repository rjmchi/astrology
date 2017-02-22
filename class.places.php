<?php
require_once 'dbclass.php';

class Places {
	public $name;
	public $country;
	public $latDeg;
	public $latDir;
	public $latMin;
	public $lngDeg;
	public $lngDir;
	public $lngMin;
	
	function __construct($name='', $country='', $lat='', $lng=''){
		$this->name = $name;
		$this->country = $country;
		if (strlen($lat)>0)
		{
			$l = explode(' ',$lat);
			$this->latDeg = (int)$l[0];
			$this->latDir = $l[1];
			$this->latMin = (int)$l[2];
		}
		if (strlen($lng)>0)
		{
			$l = explode(' ',$lng);
			$this->lngDeg = (int)$l[0];
			$this->lngDir = $l[1];
			$this->lngMin = (int)$l[2];
		}		
	}
	function Name($name='')
	{
		if ($strlen($name)>0)
		{
			$this->name = $name;
		}
		return $this->name;
	}
	
	function Country($country='')
	{
		if ($strlen($country)>0)
		{
			$this->country = $country;
		}
		return $this->country;
	}	
	
	function Longitude($lng = '')
	{
		if (strlen($lng)>0)
		{
			$l = explode(' ',$lng);
			$this->lngDeg = (int) $l[0];
			$this->lngDir = $l[1];
			$this->lngMin = (int) $l[2];			
		}
	}
	
	function Latitude($lat=''){
		if (strlen($lat)>0)
		{
			$l = explode(' ',$lat);
			$this->latDeg = (int)$l[0];
			$this->latDir = $l[1];
			$this->latMin = (int)$l[2];
		}		
	}
	
}

$prec = new dbRecord('places');

$place = new Places('Boston, MA', 'USA','42 N 21', '71 W 4');

$prec->Write($place);

for ($i=0;$i<$prec->recs;$i++)
{
	$p = $prec->ReadNext();
	var_dump($p);
}

