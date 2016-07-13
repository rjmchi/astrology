<?php
	require_once("class.planets.php");

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
		echo '<br>';
		$planet = $p->planets[$i];
		echo $planet->longName;
		echo DecToZod($planet->long);
	}
	
	var_dump($p->planets);
