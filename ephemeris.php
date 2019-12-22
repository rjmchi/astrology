<?php
require_once("class.convert.php");
require_once("class.planets.php");

	$tz = new DateTimeZone('UTC');
	$now = new DateTime('now', $tz);
	$gmt = $now->format('H') + ($now->format('i')/60);
	
	$cur_month = $now->format('m');
	$cur_year = $now->format('Y');
	
	if (isset($_POST['submit']))
	{
		if (isset($_POST['month']))
		{
			$cur_month = $_POST['month'];
		}
		if (isset($_POST['year']))
		{
			$cur_year = $_POST['year'];
		}
	}
	$days_in_month = cal_days_in_month(CAL_GREGORIAN,$cur_month,$cur_year);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Ephemeris for <?php echo $cur_month;?>/<?php echo $cur_year;?></title>
<style type="text/css">
@font-face {
    font-family: "astro";
    src: url(AstroGadget.ttf) format("truetype");
}
body {
}
span.glyph {
	font-family: 'astro';	
}
table {
	border:1px solid #000;
	border-collapse:collapse;
}
td {
	border:1px solid #000;
}
th {
	font-size:20px;
}
.odd {
	background: #CFF;
}
.even {
	background:#9FC;
}
</style>

</head>

<body>

<h2>Ephemeris for <?php echo $cur_month;?>/<?php echo $cur_year;?></h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id="date">
<label for="date">Enter Month</label>
<input type="text" name="month" value = "<?php echo $cur_month;?>">
<label for="year">Enter Year</label>
<input type="text" name="year" value = "<?php echo $cur_year;?>">
<input name="submit" type="submit" value="Submit">
</form>
<table>
	<tr>
		<th>Day</th>
		<th><span class="glyph">A</span></th>
		<th><span class="glyph">B</span></th>
		<th><span class="glyph">C</span></th>
		<th><span class="glyph">D</span></th>
		<th><span class="glyph">E</span></th>
		<th><span class="glyph">F</span></th>
		<th><span class="glyph">G</span></th>
		<th><span class="glyph">H</span></th>
		<th><span class="glyph">I</span></th>
		<th><span class="glyph">K</span></th>
	</tr>
<?php
	$odd = true;
	for ($cur_day = 1; $cur_day <= $days_in_month; $cur_day++)
	{
		$odd = !$odd;
?>
	<tr class="<?php echo ($odd)?'odd':'even';?>">
		<td><?php echo $cur_day;?></td>
<?php
		$planets = new Planets($cur_month, $cur_day, $cur_year, 0);
?>
			<td><span class="glyph"><?php echo Convert::DecToZodGlyph($planets->planets[0]->long);?></span></td> <!-- Sun-->
			<td><span class="glyph"><?php echo Convert::DecToZodGlyph($planets->planets[9]->long);?></span></td> <!-- moon-->
<?php
		for ($i=1;$i<9;$i++)
		{
			$p = $planets->planets[$i];
?>
			<td><span class="glyph"><?php echo Convert::DecToZodGlyph($p->long) . $p->rx;?></span></td>
<?php
		}
?>
		</tr>
<?php
	}
?>
	</table>

</body>
</html>