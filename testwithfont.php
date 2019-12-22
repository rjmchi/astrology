<?php
require_once( "class.convert.php" );
require_once( "class.houses.php" );
require_once( "class.planets.php" );

$mm = 7;
$dd = 30;
$yyyy = 1956;
$hh = 3;
$min = 40;
$tz = 6;
$localtime = ( $min / 60.0 ) + $hh;
$gmt = $localtime + $tz;
$latDeg = 41;
$latMin = 52;
$longDeg = 87;
$longMin = 39;

$lat = $latDeg + ( $latMin / 60 );
$long = $longDeg + ( $longMin / 60 );


$h = new houses( $mm, $dd, $yyyy, $gmt, $lat, $long );
$h->PlacHouses();

$planets = new planets( $mm, $dd, $yyyy, $gmt );

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Astro Test</title>
<style type="text/css">
@font-face {
    font-family: "astro";
    src: url(AstroGadget.ttf) format("truetype");
}
p {
    font-size: 20px;
}
span.glyph {
    font-family: 'astro';
}
</style>
</head>

<body>
<div>
    <p>Placidius Houses</p>
	<?php 
		
	for ($i=0;$i<12;$i++) {
	?>
	<p><?php echo $i+1 . ' &mdash; ' . Convert::DecToZodGlyph( $h->house[ $i ] )?> </p>
	<?php }?>
			
	<p>Planets</p>
							 
    <?php
    foreach ( $planets->planets as $p ) {
    	?>
    <p><span class="glyph"><?php echo $p->glyph;?></span>
        <?php
        echo Convert::DecToZodGlyph( $p->long );
        }
        ?>
    </p>
</div>
</body>
</html>