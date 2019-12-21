<?php
	require_once("class.convert.php");
	require_once("class.planets.php");

	$mm = 7;
	$dd=18;
	$yyyy=1941;
	$hh=16;
	$min=3;
	$tz = 5;
	$localtime = ($min/60.0)+$hh;
	$gmt= $localtime+$tz;

	$planets = new planets($mm,$dd,$yyyy,$gmt);	


	foreach($planets->planets as $p)
	{
		echo '<br>';
		echo $p->longName;
		echo Convert::DecToZod($p->long);
	}
	
