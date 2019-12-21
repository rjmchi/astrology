<?php
require_once("class.convert.php");
require_once("class.planets.php");

$month = 10;
$day = 1;
$year = 2012;
$hour = 22;
$minute = 32;
$gmt = $hour + ($minute/60);
	$planets = new Planets($month, $day, $year, $gmt);
?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>

<?php 
	echo "<h2>$month/$day/$year - $hour:$minute</h2>";

	if ($planets->MoonVoidOfCourse())
	{
		echo "<p>moon is void of course</p>";
	}
	else
	{
		echo "<p>Moon is not VOC</p>";
	}
	
	for ($i=0;$i<10;$i++)
	{
		echo '<p>' . $planets->planets[$i]->longName . ' ' . Convert::DecToZod($planets->planets[$i]->long).  ' - ' . $planets->planets[$i]->long .'</p>';
	}
?>
</body>
</html>