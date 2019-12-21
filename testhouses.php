<?php
	require_once("class.convert.php");
	require_once("class.houses.php");

	$mm = 7;
	$dd=18;
	$yyyy=1941;
	$hh=16;
	$min=3;
	$tz = 5;
	$localtime = ($min/60.0)+$hh;
	$gmt= $localtime+$tz;
	$lat = 40.033;
	$long = 76.3;
	

	$h = new houses($mm,$dd,$yyyy,$gmt, $lat, $long);	
	$h->PlacHouses();
	echo "Placidius Houses<br>";
	printHouses($h->house);
	
	$h->KochHouses();
	echo "<br>Koch Houses<br>";
	printHouses($h->house);
	
function printHouses($h)
{
	for ($i=0;$i<12;$i++)
	{
		echo $i+1 . ' - '. Convert::DecToZod($h[$i]) . '<br>';
	}
}
