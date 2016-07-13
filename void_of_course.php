<?php
require_once("class.planets.php");
$date = new DateTime();
$year = $date->format('Y');
if (isset($_POST['submit']))
{
	$month = $_POST['month'];
}
else
{
	$month = $date->format('m');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>

<?php
	$voc = false;
	$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	for ($d=0;$d<$days;$d++)
	{
		for ($h = 0;$h<24;$h++)
		{
			for ($m = 0; $m < 60;$m+=1)
			{
				$gmt = $h + ($m/60);
				$planets = new Planets($month, $d+1, $year, $gmt);
				if ($h > 12)
				{
					$h1 = $h-12;
					$ap = "pm";
				}
				else
				{
					$h1 = $h;
					$ap = "am";
				}
				set_time_limit (60);
				if ($planets->MoonVoidOfCourse())
				{
					if (!$voc)
					{
						$voc = true;
						echo '<p>' . DecToZod($planets->planets[9]->long).  '</p>';
						echo '<p>' . $month . '/' . $d . '/' . $year . ' - ' . $h1 . ':' . $m .  $ap . '</p>';
					}
				}
				else
				{
					if ($voc)
					{
						echo '<p>Ends - ' . $month . '/' . $d . '/' . $year . ' - ' . $h1 . ':' . $m . $ap. '</p>';
						$voc = false;
					}
				}
			}
		}
	}
?>
	<form action="<?php echo $_SERVER['php_self'];?>" method="post">
		<select name="month">
			<option value="1">January</option>
			<option value="2">February</option>
			<option value="3">March</option>
			<option value="4">April</option>
			<option value="5">May</option>
			<option value="6">June</option>
			<option value="7">July</option>
			<option value="8">August</option>
			<option value="9">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		</select>
		<input type="submit" name="submit" value="Submit">
	</form>
</body>
</html>