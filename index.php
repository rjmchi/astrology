<?php
require_once("cnvt.php");
require_once("class.planets.php");
require_once("class.houses.php");
	$msg = '';
	$dateerror = '';
	$timeerror = '';
	$laterror = '';
	$lngerror = '';
	$timezoneerror = '';
	$error = false;
	$display = false;
	$name = getVar('name');
	$mm = getVar('mm');
	$dd = getVar('dd');
	$yyyy = getVar('yyyy');
	$hours = getVar('hours');
	$minutes = getVar('minutes', '00');
	$ampm = getVar('ampm', 'am');
	$city = getVar('city');
	$state = getVar('state');
	$country = getVar('country');
	$latdeg = getVar('latdeg');
	$latmin = getVar('latmin');
	$latns = getVar('latns' , '1');
	$lngdeg = getVar('lngdeg');
	$lngmin = getVar('lngmin');
	$lngew = getVar('lngew', '1');
	$timezone = getVar('timezone');
	$housesystem = getVar('housesystem', '0');
	
	$tz = new DateTimeZone('UTC');
	$now = new DateTime('now', $tz);
	
//	date_add($now, date_interval_create_from_date_string($now->getOffset().'s'));
//	date_add($now, date_interval_create_from_date_string("5h"));
	$gmt = $now->format('H') + ($now->format('i')/60);
	$transit = new planets($now->format('m'),$now->format('d'),$now->format('Y'),$gmt);	
	
	if (isset($_POST['submit']))
	{
		if (!is_numeric($mm) || !is_numeric($dd) || !is_numeric($yyyy))
		{
			$dateerror = 'Please enter Date';
		}
		else if (!checkdate($_POST['mm'], $_POST['dd'], $_POST['yyyy']))
		{
			$dateerror = 'Bad date';
			$error=true;
		}
		if (!is_numeric($_POST['hours']))
		{
			$timeerror = 'Please enter time';
			$error=true;
		}
		else if ($_POST['hours'] > 12 || $_POST['minutes'] > 59)
		{
			$timeerror = 'Bad Time';
			$error=true;
		}
		if (!is_numeric($_POST['latdeg']) || !is_numeric($_POST['latmin']) || !is_numeric($_POST['latns']))
		{
			$laterror = 'Please enter Latitude';
			$error=true;
		}
		else if ($_POST['latdeg'] > 90 || $_POST['latmin'] > 59)
		{
			$laterror = 'Bad Latitude';
			$error=true;
		}
		if (!is_numeric($_POST['lngdeg']) || !is_numeric($_POST['lngmin']) || !is_numeric($_POST['lngew']))
		{
			$lngerror = 'Please enter Longitude';
			$error=true;
		}
		else if ($_POST['lngdeg'] > 180 || $_POST['lngmin'] > 59)
		{
			$lngerror = 'Bad Longitude';
			$error=true;
		}
		if (is_numeric($_POST['timezone']))
		{
			if (($_POST['timezone'] > 12) || ($_POST['timezone'] < -12))
			{
				$timezoneerror = 'Invalid Time Zone';
				$error = true;
			}
		}
		else
		{
			$timezoneerror = 'Time Zone must be numeric';
			$error = true;
		}
		if (!$error)
		{
			$lat = ($_POST['latdeg'] + ($_POST['latmin']/60)) * $_POST['latns'];
			$lng = ($_POST['lngdeg'] + ($_POST['lngmin']/60)) * $_POST['lngew'];
			$local_time = $hours + $minutes/60;
			if ($ampm == 'pm')
			{
				$local_time += 12;
			}
			$gmt = $local_time + $timezone;
			$p = new planets($mm,$dd,$yyyy,$gmt);	
			$h = new Houses($mm, $dd, $yyyy, $gmt, $lat, $lng);	
			switch ($housesystem)
			{
				case 0:
					$h->PlacHouses();
				break;
				case 1:
					$h->KochHouses();
				break;
				case 2:
					$h->EqualHouses();
				break;
			}
			$display= true;
		}
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Natal Chart</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<p><?php echo $now->format('m/d/Y  H:i:s');?></p>
<p><?php echo $transit->planets[9]->longName;?> 
<?php echo DecToZod($transit->planets[9]->long);?></p>
<?php 
	if ($transit->MoonVoidOfCourse())
	{
		echo "<p>Moon Void of Course</p>";
	}
?>

<?php 
	if ($transit->planets[1]->rx == 'Rx')
	{
		echo '<p>' . $transit->planets[1]->longName . ' ' . $transit->planets[1]->rx . '</p>';
	}
?>

<form name="form1" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
	<fieldset>
		<label for="name">Name:</label>
		<input name="name" type="text" id="name" size="40" value="<?php echo $name;?>">
	</fieldset>
	<fieldset>
		Birthdate:
		<label for="mm">Month:</label>
		<input name="mm" type="text" id="mm" size="2" maxlength="2" value="<?php echo $mm;?>">
		<label for="dd">Day:</label>
		<input name="dd" type="text" id="dd" size="2" maxlength="2" value="<?php echo $dd;?>">
		<label for="yyyy">Year:</label>
		<input name="yyyy" type="text" id="yyyy" size="4" maxlength="4" value="<?php echo $yyyy;?>">
		<span class="error"><?php echo $dateerror;?></span>
	</fieldset>
	<fieldset>
		Birth Time:
		<label for="hours">Hours:</label>
		<input name="hours" type="text" id="hours" size="2" maxlength="2" value="<?php echo $hours;?>">
		<label for="minutes">Minutes:</label>
		<input name="minutes" type="text" id="minutes" size="2" maxlength="2" value="<?php echo $minutes;?>">
		<label>
			<input type="radio" name="ampm" value="am" id="ampm_0" <?php echo ($ampm == 'am') ? ' checked ' : '';?>>
			AM</label>
		<label>
			<input type="radio" name="ampm" value="pm" id="ampm_1" <?php echo ($ampm == 'pm') ? ' checked ' : '';?>>
			PM</label>
		<span class="error"><?php echo $timeerror;?></span>
	</fieldset>
	<fieldset>
		Birth Place:
		<label for="city">City:</label>
		<input name="city" type="text" id="city" size="30" value="<?php echo $city;?>">
		<label for="state">State:</label>
		<input type="text" name="state" id="state" value="<?php echo $state;?>">
		<label for="country">Country:</label>
		<input name="country" type="text" id="country" size="30" value="<?php echo $country;?>">
	</fieldset>
	<fieldset>
		Latitude
		<label for="latdeg">Deg:</label>
		<input name="latdeg" type="text" id="latdeg" size="2" maxlength="2" value="<?php echo $latdeg;?>">
		<label>
			<input type="radio" name="latns" value="1" id="latn" <?php echo ($latns > 0) ? ' checked ' : '';?>>
			N</label>
		<label>
			<input type="radio" name="latns" value="-1" id="lats" <?php echo ($latns < 0) ? ' checked ' : '';?>>
			S</label>
		<label for="latmin">Min:</label>
		<input name="latmin" type="text" id="latmin" size="2" maxlength="2" value="<?php echo $latmin;?>">
		<span class="error"><?php echo $laterror;?></span>
	</fieldset>
	<fieldset>
		Longitude:
		<label for="lngdeg">Deg:</label>
		<input name="lngdeg" type="text" id="lngdeg" size="3" maxlength="3" value="<?php echo $lngdeg;?>">
		<label>
			<input type="radio" name="lngew" value="-1" id="lnge" <?php echo ($lngew < 0) ? ' checked ' : '';?>>
		E</label>
		<label>
			<input type="radio" name="lngew" value="1" id="lngw" <?php echo ($lngew > 0) ? ' checked ' : '';?>>
		W</label>
		<label for="lngmin">Min:</label>
		<input name="lngmin" type="text" id="lngmin" size="2" maxlength="2" value="<?php echo $lngmin;?>">
		<span class="error"><?php echo $lngerror;?></span>
	</fieldset>
	<fieldset>
		<label for="timezone">Time Zone:</label>
		<input name="timezone" type="text" size="5" value="<?php echo $timezone;?>">
		<span class="error"><?php echo $timezoneerror;?></span>
	</fieldset>
	<fieldset>House System
	<select name="housesystem" id="housesystem">
		<option value="0" <?php echo ($housesystem == 0) ? 'selected': '';?>>Placidius</option>
		<option value="1" <?php echo ($housesystem == 1) ? 'selected': '';?>>Koch</option>
		<option value="2" <?php echo ($housesystem == 2) ? 'selected': '';?>>Equal</option>
	</select>
	</fieldset>
	<input name="submit" type="submit" value="Submit">
</form>

<?php
if ($display)
{
	$odd = true;
?>
	<table class="planets">
		<tr>
			<th>Name</th>
			<th>Longitude</th>
			<th>Latitude</th>
			<th>Declination</th>
		</tr>
<?php 
	foreach ($p->planets as $planet)
	{
		$odd = !$odd;
?>
		<tr class="<?php echo ($odd)? 'odd': 'even';?>">
			<td><?php echo $planet->longName;?></td>
			<td><?php echo DecToZod($planet->long);?></td>
			<td><?php echo DecToLat($planet->lat);?></td>
			<td><?php echo DecToLat($planet->dcl);?></td>
		</tr>
<?php
	}	
?>
	</table>
	<h2>Houses</h2>
<?php 
	for ($i=0;$i<12;$i++)
	{
		echo "<p>House ";
		echo $i+1;
		echo ": " . DecToZod($h->house[$i]) . "</p>";
	}
	echo "<p>East Point: ";
	echo DecToZod($h->ep) . "</p>";
	echo "<p>Vertex: ";
	echo DecToZod($h->vx) . "</p>";
?>
<h2>Aspects</h2>
<?php
	for ($p1=0;$p1<11;$p1++)
	{
		for ($p2=$p1+1;$p2<12;$p2++)
		{
			$diff = abs($p->planets[$p1]->long - $p->planets[$p2]->long);
			echo "<p>".$p->planets[$p1]->longName . " " . $diff . " " . $p->planets[$p2]->longName .  "</p>";
		}
	}
?>
<?php
}
?>
</body>
</html>
<?php
function getVar($var, $default='')
{
	if (isset($_POST[$var]))
	{
		return $_POST[$var];
	}
	else
	{
		return $default;
	}
}
?>