<?php
/*Various conversions
	DecToLong - converts decimal value to longitude
	DecToLat  - converts decimal value to latitude
	DecToTime - converts decimal value to time - 12 or 24 hour clock
	RecToPol  - converts rectangular coordinates to polar
	PolToRec  - converts polar coordinates to rectangular
	DecToZod  - converts decimal to zodiac
	DecToZodGlyph - comverts decimal to zodiac using charater for glyph
	Mod360
*/

define("SMALL",  1.7453e-09);
class Convert {

	static public function RecToPol($x, $y)
	{
		if (! $y)
			$y = SMALL;
		$r = sqrt($x * $x + $y * $y);
		$a = atan($y / $x);
		if ($a < 0)
			$a += M_PI;
		if ($y < 0)
            $a += M_PI;
        return array('a'=>$a, 'r'=>$r);
    }

	static public function PolToRec($a, $r)
	{
		$x = $r * cos($a);
        $y = $r * sin($a);
        return array('x'=>$x,'y'=>$y);
    }

    static public function DecToTime ($dec_time, $clock_type)
    {
        $time_hours = (int) $dec_time;
        $dec_time -= $time_hours;
        $dec_time = $dec_time * 60.0 +.5;

        $time_min = (int) $dec_time;
        if ($clock_type == 12)
        {
            if ($time_hours >= 12)
            {
                if ($time_hours > 12)
                    $time_hours -= 12;
                $am_pm = 'P';
            }
            else
            {
                if ($time_hours == 0)
                    $time_hours += 12;
                $am_pm = 'A';
            }
            $txtTime = sprintf ("%d:%02d %sM", $time_hours, $time_min, $am_pm);
        }
        else
            $txtTime = sprintf ("%d:%02d", $time_hours, $time_min);

        return $txtTime;
    }

    static public function DecToLong($dec_lng)
    {
        if ($dec_lng < 0)
            $dir= 'E';
        else
            $dir = 'W';
        $dec_lng = abs($dec_lng);
        $lng_deg = (int) $dec_lng;
        $dec_lng -= $lng_deg;
        $dec_lng = $dec_lng * 60.0 + .5;
        $lng_min = (int) $dec_lng;
        $txtLong = sprintf ("%d%s%02d",$lng_deg,$dir,$lng_min);
        return $txtLong;
    }
    static public function DecToLat($dec_lat)
    {
        if ($dec_lat < 0)
            $dir = 'S';
        else
            $dir = 'N';
        $dec_lat = abs($dec_lat);
        $lat_deg = (int) $dec_lat;
        $dec_lat -= $lat_deg;
        $dec_lat = $dec_lat * 60.0 + .5;
        $lat_min = (int) $dec_lat;
        $txtLat = sprintf ("%d%s%02d",$lat_deg,$dir,$lat_min);
        return $txtLat;
    }

    static public function Mod360($x)
    {
    /*  returns result within circle    */
    if (!is_numeric($x)){
        echo '--';
        die ($x);
        echo '--';
    }
        $x = ( $x - ((int)($x / 360)) * 360.0);
        if ($x < 0)
        {
            $x = 360.0 + $x;
        }
        return $x;
    }

    static public function Mod2Pi($x)
    /*  returns result within circle in radians    */
    {
        return ( $x - ((int)($x /( M_PI*2))) * M_PI*2);
    }
    static public function DecToZod($plce)
    {
    $SignNames= array(' ARI ', ' TAU ', ' GEM ', ' CAN ', ' LEO ', ' VIR ', ' LIB ', ' SCO ', ' SAG ', ' CAP ', ' AQU ', ' PIC ');
        $s = (int) ($plce / 30.0);
        $plce -= ($s * 30);
        $d = (int) $plce;
        $m = (int) ((($plce - $d) * 60.0) + .5);

        if ($m >=60)
        {
            $m -=60;
            $d++;
        }
        if ($d >= 30)
        {
            $d-=30;
            $s++;
        }
        if ($s >=12)
        {
            $s-=12;
        }

        $zod = sprintf("%d %s %02d", $d, $SignNames[$s], $m);
        return $zod;
    }

    static public function DecToZodGlyph($plce)
    {
    $SignNames= array(' a ', ' b ', ' c ', ' d ', ' e ', ' f ', ' g ', ' h ', ' i ', ' j ', ' k ', ' l ');
        $s = (int) ($plce / 30.0);
        $plce -= ($s * 30);
        $d = (int) $plce;
        $m = (int) ((($plce - $d) * 60.0) + .5);

        if ($m >=60)
        {
            $m -=60;
            $d++;
        }
        if ($d >= 30)
        {
            $d-=30;
            $s++;
        }
        if ($s >=12)
        {
            $s-=12;
        }

        $zod = sprintf('%d <span class="glyph">%s</span> %02d', $d, $SignNames[$s], $m);
        return $zod;
    }
}