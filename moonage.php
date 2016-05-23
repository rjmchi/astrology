<?php require_once('class.planets.php');?>


<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>

<p>Moon's Age (Number of Days past the New Moon)</p>
<?php
$cur_month = 12;
$cur_year = 2014;
$days_in_month = cal_days_in_month(CAL_GREGORIAN,$cur_month,$cur_year);

for ($i=0;$i < $days_in_month;$i++)
{
	$age = MoonAge($i+1,$cur_month,$cur_year);
	if ($age == 1)
	{
		$planets = new Planets($cur_month, $i, $cur_year, 0);
		$sun = $planets->planets[0];
		$moon = $planets->planets[9];
		$diff = abs($sun->long - $moon->long);
//		if ($sun->long > $moon->long)
//		{
//			$diff = $sun->long - $moon->long;
//		}
//		else
//		{
//			$diff = $moon->lng - $sun->lng;
//		}
		echo ' new moon ';
		echo $diff;
	}

	echo "<br>";
	echo  $i+1 . " - " . $age;
}
?>
</body>
</html>


<?php
function JulianDate($d, $m, $y)
{ 

    $yy = $y - (int)((12 - $m) / 10);
    $mm = $m + 9;
    if ($mm >= 12)
    {
        $mm = $mm - 12;
    }
    $k1 = (int)(365.25 * ($yy + 4712));
    $k2 = (int)(30.6001 * $mm + 0.5);
    $k3 = (int)((int)(($yy / 100) + 49) * 0.75) - 38;
    // 'j' for dates in Julian calendar:
    $j = $k1 + $k2 + $d + 59;
    if ($j > 2299160)
    {
        // For Gregorian calendar:
        $j = $j - $k3; // 'j' is the Julian date at 12h UT (Universal Time)
    }
    return $j;
}


function MoonAge($d, $m, $y)
{ 
    $j = JulianDate($d, $m, $y);
    //Calculate the approximate phase of the moon
    $ip = ($j + 4.867) / 29.53059;
   $ip = $ip - floor($ip); 
    //After several trials I've seen to add the following lines, 
    //which gave the result was not bad 
    if($ip < 0.5)
        $ag = $ip * 29.53059 + 29.53059 / 2;
    else
        $ag = $ip * 29.53059 - 29.53059 / 2;
    // Moon's age in days
    $ag = floor($ag) + 1;
    return $ag;
}