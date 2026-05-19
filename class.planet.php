<?php
/**
 * Planet – value object representing a single celestial body.
 *
 * Populated by the Planets calculator; consumers read the public properties.
 */
class Planet
{
    // Identification
    public int    $id;
    public string $longName;
    public string $shortName;
    public string $glyph;

    // Computed position (set by Planets)
    public float  $long = 0.0;   // ecliptic longitude, decimal degrees
    public float  $lat  = 0.0;   // ecliptic latitude,  decimal degrees
    public float  $dcl  = 0.0;   // declination,        decimal degrees
    public string $rx   = ' ';   // retrograde marker: 'Rx' or ' '

    private const LONG_NAMES  = [
        'Sun', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn',
        'Uranus', 'Neptune', 'Pluto', 'Moon',
        'North Node', 'South Node', 'Mean Node',
    ];
    private const SHORT_NAMES = [
        'SU', 'ME', 'VE', 'MA', 'JU', 'SA',
        'UR', 'NE', 'PL', 'MO', 'NN', 'SN', 'MN',
    ];
    private const GLYPHS = [
        'A', 'C', 'D', 'E', 'F', 'G',
        'H', 'I', 'K', 'B', 'L', 'M', 'L',
    ];

    public function __construct(int $id)
    {
        $this->id        = $id;
        $this->longName  = self::LONG_NAMES[$id];
        $this->shortName = self::SHORT_NAMES[$id];
        $this->glyph     = self::GLYPHS[$id];
    }
}