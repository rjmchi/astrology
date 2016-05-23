<?php
	require_once("cnvt.php");
	require_once("class.planets.php");
	require_once("class.houses.php");

	$mm = 7;
	$dd=18;
	$yyyy=1941;
	$hh=16;
	$min=3;
	$tz = 5;
	$localtime = ($min/60.0)+$hh;
	$gmt= $localtime+$tz;

	$p = new planets($mm,$dd,$yyyy,$gmt);	
	
	for ($i=0;$i<10;$i++)
	{
		$planet = $p->planets[$i];
		echo $planet->longName;
		echo DecToZod($planet->long);
		echo '<br>';
	}
	
	echo "<br>";
	
	$lat = 40.033;
	$lng = 76.3;
	
	$h = new houses($mm, $dd, $yyyy, $gmt, $lat, $lng);
	
	$h->PlacHouses();
	echo "Placidius House System<br>";
	printHouses($h->house);
	
	$h->KochHouses();
	echo "<br>Koch House System<br>";
	printHouses($h->house);
	
	$h->EqualHouses();
	echo "<br>Equal House System<br>";
	printHouses($h->house);
	
	$h->TopocentricHouses();
	echo "<br>Topocentric House System<br>";
	printHouses($h->house);	
	
function printHouses($h)
{
	for ($i=0;$i<12;$i++)
	{
		echo $i+1 . ' - '. DecToZod($h[$i]) . '<br>';
	}
}
