<?php
/**
 * Created by AbdolRahman Damia
 * User: Home
 * Date: 1/15/14
 * Time: 1:43 PM
 *
 * A class for positional astronomy
 *
 */

namespace Dahatu\Dates;

// constants
define('J2000', 2451545.0);                      // Julian day of J2000 epoch
define('JulianCentury', 36525.0);                // Days in Julian century
define('JulianMillennium', JulianCentury * 10);    // Days in Julian millennium
define('AstronomicalUnit', 149597870.0);         // Astronomical unit in kilometres
define('TropicalYear', 365.24219878);            // Mean solar tropical year

class PositionalAstronomy
{

    /*  ASTOR  --  Arc-seconds to radians.  */
    private static $weekDays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

    /*  DTR  --  Degrees to radians.  */
    private static $oterms = Array(-4680.93,
        -1.55,
        1999.25,
        -51.38,
        -249.67,
        -39.05,
        7.12,
        27.87,
        5.79,
        2.45);

    /*  RTD  --  Radians to degrees.  */
    private static $nutArgMult = Array(0, 0, 0, 0, 1,
        -2, 0, 0, 2, 2,
        0, 0, 0, 2, 2,
        0, 0, 0, 0, 2,
        0, 1, 0, 0, 0,
        0, 0, 1, 0, 0,
        -2, 1, 0, 2, 2,
        0, 0, 0, 2, 1,
        0, 0, 1, 2, 2,
        -2, -1, 0, 2, 2,
        -2, 0, 1, 0, 0,
        -2, 0, 0, 2, 1,
        0, 0, -1, 2, 2,
        2, 0, 0, 0, 0,
        0, 0, 1, 0, 1,
        2, 0, -1, 2, 2,
        0, 0, -1, 0, 1,
        0, 0, 1, 2, 1,
        -2, 0, 2, 0, 0,
        0, 0, -2, 2, 1,
        2, 0, 0, 2, 2,
        0, 0, 2, 2, 2,
        0, 0, 2, 0, 0,
        -2, 0, 1, 2, 2,
        0, 0, 0, 2, 0,
        -2, 0, 0, 2, 0,
        0, 0, -1, 2, 1,
        0, 2, 0, 0, 0,
        2, 0, -1, 0, 1,
        -2, 2, 0, 2, 2,
        0, 1, 0, 0, 1,
        -2, 0, 1, 0, 1,
        0, -1, 0, 0, 1,
        0, 0, 2, -2, 0,
        2, 0, -1, 2, 1,
        2, 0, 1, 2, 2,
        0, 1, 0, 2, 2,
        -2, 1, 1, 0, 0,
        0, -1, 0, 2, 2,
        2, 0, 0, 2, 1,
        2, 0, 1, 0, 0,
        -2, 0, 2, 2, 2,
        -2, 0, 1, 2, 1,
        2, 0, -2, 0, 1,
        2, 0, 0, 0, 1,
        0, -1, 1, 0, 0,
        -2, -1, 0, 2, 1,
        -2, 0, 0, 0, 1,
        0, 0, 2, 2, 1,
        -2, 0, 2, 0, 1,
        -2, 1, 0, 2, 1,
        0, 0, 1, -2, 0,
        -1, 0, 1, 0, 0,
        -2, 1, 0, 0, 0,
        1, 0, 0, 0, 0,
        0, 0, 1, 2, 0,
        -1, -1, 1, 0, 0,
        0, 1, 1, 0, 0,
        0, -1, 1, 2, 2,
        2, -1, -1, 2, 2,
        0, 0, -2, 2, 2,
        0, 0, 3, 2, 2,
        2, -1, 0, 2, 2);

    /*  FIXANGLE  --  Range reduce angle in degrees.  */
    private static $nutArgCoeff = Array(-171996, -1742, 92095, 89,          /*  0,  0,  0,  0,  1 */
        -13187, -16, 5736, -31,          /* -2,  0,  0,  2,  2 */
        -2274, -2, 977, -5,          /*  0,  0,  0,  2,  2 */
        2062, 2, -895, 5,          /*  0,  0,  0,  0,  2 */
        1426, -34, 54, -1,          /*  0,  1,  0,  0,  0 */
        712, 1, -7, 0,          /*  0,  0,  1,  0,  0 */
        -517, 12, 224, -6,          /* -2,  1,  0,  2,  2 */
        -386, -4, 200, 0,          /*  0,  0,  0,  2,  1 */
        -301, 0, 129, -1,          /*  0,  0,  1,  2,  2 */
        217, -5, -95, 3,          /* -2, -1,  0,  2,  2 */
        -158, 0, 0, 0,          /* -2,  0,  1,  0,  0 */
        129, 1, -70, 0,          /* -2,  0,  0,  2,  1 */
        123, 0, -53, 0,          /*  0,  0, -1,  2,  2 */
        63, 0, 0, 0,          /*  2,  0,  0,  0,  0 */
        63, 1, -33, 0,          /*  0,  0,  1,  0,  1 */
        -59, 0, 26, 0,          /*  2,  0, -1,  2,  2 */
        -58, -1, 32, 0,          /*  0,  0, -1,  0,  1 */
        -51, 0, 27, 0,          /*  0,  0,  1,  2,  1 */
        48, 0, 0, 0,          /* -2,  0,  2,  0,  0 */
        46, 0, -24, 0,          /*  0,  0, -2,  2,  1 */
        -38, 0, 16, 0,          /*  2,  0,  0,  2,  2 */
        -31, 0, 13, 0,          /*  0,  0,  2,  2,  2 */
        29, 0, 0, 0,          /*  0,  0,  2,  0,  0 */
        29, 0, -12, 0,          /* -2,  0,  1,  2,  2 */
        26, 0, 0, 0,          /*  0,  0,  0,  2,  0 */
        -22, 0, 0, 0,          /* -2,  0,  0,  2,  0 */
        21, 0, -10, 0,          /*  0,  0, -1,  2,  1 */
        17, -1, 0, 0,          /*  0,  2,  0,  0,  0 */
        16, 0, -8, 0,          /*  2,  0, -1,  0,  1 */
        -16, 1, 7, 0,          /* -2,  2,  0,  2,  2 */
        -15, 0, 9, 0,          /*  0,  1,  0,  0,  1 */
        -13, 0, 7, 0,          /* -2,  0,  1,  0,  1 */
        -12, 0, 6, 0,          /*  0, -1,  0,  0,  1 */
        11, 0, 0, 0,          /*  0,  0,  2, -2,  0 */
        -10, 0, 5, 0,          /*  2,  0, -1,  2,  1 */
        -8, 0, 3, 0,          /*  2,  0,  1,  2,  2 */
        7, 0, -3, 0,          /*  0,  1,  0,  2,  2 */
        -7, 0, 0, 0,          /* -2,  1,  1,  0,  0 */
        -7, 0, 3, 0,          /*  0, -1,  0,  2,  2 */
        -7, 0, 3, 0,          /*  2,  0,  0,  2,  1 */
        6, 0, 0, 0,          /*  2,  0,  1,  0,  0 */
        6, 0, -3, 0,          /* -2,  0,  2,  2,  2 */
        6, 0, -3, 0,          /* -2,  0,  1,  2,  1 */
        -6, 0, 3, 0,          /*  2,  0, -2,  0,  1 */
        -6, 0, 3, 0,          /*  2,  0,  0,  0,  1 */
        5, 0, 0, 0,          /*  0, -1,  1,  0,  0 */
        -5, 0, 3, 0,          /* -2, -1,  0,  2,  1 */
        -5, 0, 3, 0,          /* -2,  0,  0,  0,  1 */
        -5, 0, 3, 0,          /*  0,  0,  2,  2,  1 */
        4, 0, 0, 0,          /* -2,  0,  2,  0,  1 */
        4, 0, 0, 0,          /* -2,  1,  0,  2,  1 */
        4, 0, 0, 0,          /*  0,  0,  1, -2,  0 */
        -4, 0, 0, 0,          /* -1,  0,  1,  0,  0 */
        -4, 0, 0, 0,          /* -2,  1,  0,  0,  0 */
        -4, 0, 0, 0,          /*  1,  0,  0,  0,  0 */
        3, 0, 0, 0,          /*  0,  0,  1,  2,  0 */
        -3, 0, 0, 0,          /* -1, -1,  1,  0,  0 */
        -3, 0, 0, 0,          /*  0,  1,  1,  0,  0 */
        -3, 0, 0, 0,          /*  0, -1,  1,  2,  2 */
        -3, 0, 0, 0,          /*  2, -1, -1,  2,  2 */
        -3, 0, 0, 0,          /*  0,  0, -2,  2,  2 */
        -3, 0, 0, 0,          /*  0,  0,  3,  2,  2 */
        -3, 0, 0, 0           /*  2, -1,  0,  2,  2 */
    );

    /*  FIXANGR  --  Range reduce angle in radians.  */
    private static $deltaTtab = Array(121, 112, 103, 95, 88, 82, 77, 72, 68, 63, 60, 56, 53, 51, 48, 46,
        44, 42, 40, 38, 35, 33, 31, 29, 26, 24, 22, 20, 18, 16, 14, 12,
        11, 10, 9, 8, 7, 7, 7, 7, 7, 7, 8, 8, 9, 9, 9, 9, 9, 10, 10, 10,
        10, 10, 10, 10, 10, 11, 11, 11, 11, 11, 12, 12, 12, 12, 13, 13,
        13, 14, 14, 14, 14, 15, 15, 15, 15, 15, 16, 16, 16, 16, 16, 16,
        16, 16, 15, 15, 14, 13, 13.1, 12.5, 12.2, 12, 12, 12, 12, 12, 12,
        11.9, 11.6, 11, 10.2, 9.2, 8.2, 7.1, 6.2, 5.6, 5.4, 5.3, 5.4, 5.6,
        5.9, 6.2, 6.5, 6.8, 7.1, 7.3, 7.5, 7.6, 7.7, 7.3, 6.2, 5.2, 2.7,
        1.4, -1.2, -2.8, -3.8, -4.8, -5.5, -5.3, -5.6, -5.7, -5.9, -6,
        -6.3, -6.5, -6.2, -4.7, -2.8, -0.1, 2.6, 5.3, 7.7, 10.4, 13.3, 16,
        18.2, 20.2, 21.1, 22.4, 23.5, 23.8, 24.3, 24, 23.9, 23.9, 23.7,
        24, 24.3, 25.3, 26.2, 27.3, 28.2, 29.1, 30, 30.7, 31.4, 32.2,
        33.1, 34, 35, 36.5, 38.3, 40.2, 42.2, 44.5, 46.5, 48.5, 50.5,
        52.2, 53.8, 54.9, 55.8, 56.9, 58.3, 60, 61.6, 63, 65, 66.6);

    //  DSIN  --  Sine of an angle in degrees
    private static $EquinoxpTerms = Array(485, 324.96, 1934.136,
        203, 337.23, 32964.467,
        199, 342.08, 20.186,
        182, 27.85, 445267.112,
        156, 73.14, 45036.886,
        136, 171.52, 22518.443,
        77, 222.54, 65928.934,
        74, 296.72, 3034.906,
        70, 243.58, 9037.513,
        58, 119.81, 33718.147,
        52, 297.17, 150.678,
        50, 21.02, 2281.226,
        45, 247.54, 29929.562,
        44, 325.15, 31555.956,
        29, 60.93, 4443.417,
        18, 155.12, 67555.328,
        17, 288.79, 4562.452,
        16, 198.04, 62894.029,
        14, 199.76, 31436.921,
        12, 95.39, 14577.848,
        12, 287.11, 31931.756,
        12, 320.81, 34777.259,
        9, 227.73, 1222.114,
        8, 15.45, 16859.074);

    //  DCOS  --  Cosine of an angle in degrees
    private static $JDE0tab1000 = Array(
        Array(1721139.29189, 365242.13740, 0.06134, 0.00111, -0.00071),
        Array(1721233.25401, 365241.72562, -0.05323, 0.00907, 0.00025),
        Array(1721325.70455, 365242.49558, -0.11677, -0.00297, 0.00074),
        Array(1721414.39987, 365242.88257, -0.00769, -0.00933, -0.00006)
    );

    /*  MOD  --  Modulus public static function which works for non-integers.  */
    private static $JDE0tab2000 = Array(
        Array(2451623.80984, 365242.37404, 0.05169, -0.00411, -0.00057),
        Array(2451716.56767, 365241.62603, 0.00325, 0.00888, -0.00030),
        Array(2451810.21715, 365242.01767, -0.11575, 0.00337, 0.00078),
        Array(2451900.05952, 365242.74049, -0.06223, -0.00823, 0.00032)
    );

    //  AMOD  --  Modulus public static function which returns numerator if modulus is zero

    public static function astor($arc)
    {
        return $arc * (M_PI / (180.0 * 3600.0));
    }

    /*  JHMS  --  Convert Julian time to hour, minutes, and seconds,
                  returned as a three-element array.  */

    public static function aMod($fA, $fB)
    {
        return positionalAstronomy::mod($fA - 1, $fB) + 1;
    }

    //  JWDAY  --  Calculate day of week from Julian day

    public static function mod($fA, $fB)
    {
        return $fA - ($fB * floor($fA / $fB));
    }

    public static function julianToHMS($jTime)
    {
        $jTime += 0.5;                 /* Astronomical to civil */
        $ij = (($jTime - floor($jTime)) * 86400.0) + 0.5;
        $h = floor($ij / 3600);
        $m = floor(($ij / 60) % 60);
        $s = floor($ij % 60);
        return array($h, $m, $s);
    }

    public static function jwDay($jDay)
    {
        return positionalAstronomy::$weekDays[positionalAstronomy::mod(floor(($jDay + 1.5)), 7)];
    }

    /*  OBLIQEQ  --  Calculate the obliquity of the ecliptic for a given
                     Julian date.  This uses Laskar's tenth-degree
                     polynomial fit (J. Laskar, Astronomy and
                     Astrophysics, Vol. 157, page 68 [1986]) which is
                     accurate to within 0.01 arc second between AD 1000
                     and AD 3000, and within a few seconds of arc for
                     +/-10000 years around AD 2000.  If we're outside the
                     range in which this fit is valid (deep time) we
                     simply return the J2000 value of the obliquity, which
                     happens to be almost precisely the mean.  */

    public static function jwDayName($jDay)
    {
        return positionalAstronomy::mod(floor(($jDay + 1.5)), 7);
    }

    public static function ecliptoeq($jd, $Lambda, $Beta)
    {
        /* Obliquity of the ecliptic. */
        $eps = positionalAstronomy::dtr(positionalAstronomy::obliqeq($jd));
        $log = "Obliquity: " . positionalAstronomy::rtd($eps) . "\n";
        $Ra = positionalAstronomy::rtd(atan2((cos($eps) * sin(positionalAstronomy::dtr($Lambda)) -
            (tan(positionalAstronomy::dtr($Beta)) * sin($eps))),
            cos(positionalAstronomy::dtr($Lambda))));
        $log .= "RA = " . $Ra . "\n";
        $Ra = positionalAstronomy::fixangle(positionalAstronomy::rtd(atan2((cos($eps) * sin(positionalAstronomy::dtr($Lambda)) -
            (tan(positionalAstronomy::dtr($Beta)) * sin($eps))),
            cos(positionalAstronomy::dtr($Lambda)))));
        $Dec = positionalAstronomy::rtd(asin((sin($eps) * sin(positionalAstronomy::dtr($Lambda)) * cos(positionalAstronomy::dtr($Beta))) +
            (sin(positionalAstronomy::dtr($Beta)) * cos($eps))));

        return Array($Ra, $Dec, $log);
    }

    /* Periodic terms for nutation in longiude (delta \Psi) and
       obliquity (delta \Epsilon) as given in table 21.A of
       Meeus, "Astronomical Algorithms", first edition. */

    public static function dtr($deg)
    {
        return ($deg * M_PI) / 180.0;
    }

    public static function obliqeq($jDate)
    {
        $v = $u = ($jDate - J2000) / (JulianCentury * 100);
        $eps = 23 + (26 / 60.0) + (21.448 / 3600.0);
        if (abs($u) < 1.0) {
            for ($i = 0; $i < 10; $i++) {
                $eps += (positionalAstronomy::$oterms[$i] / 3600.0) * $v;
                $v *= $u;
            }
        }
        return $eps;
    }

    /*  NUTATION  --  Calculate the nutation in longitude, deltaPsi, and
                      obliquity, deltaEpsilon for a given Julian date
                      jd.  Results are returned as a two element Array
                      giving (deltaPsi, deltaEpsilon) in degrees.  */

    public static function rtd($rad)
    {
        return ($rad * 180.0) / M_PI;
    }

    /*  ECLIPTOEQ  --  Convert celestial (ecliptical) longitude and
                       latitude into right ascension (in degrees) and
                       declination.  We must supply the time of the
                       conversion in order to compensate correctly for the
                       varying obliquity of the ecliptic over time.
                       The right ascension and declination are returned
                       as a two-element Array in that order.  */

    public static function fixAngle($angDeg)
    {
        return $angDeg - 360.0 * (floor($angDeg / 360.0));
    }

    /*  DELTAT  --  Determine the difference, in seconds, between
                    Dynamical time and Universal time.  */
    /*  Table of observed Delta T values at the beginning of
        even numbered years from 1620 through 2002.  */

    public static function deltat($year)
    {
        if (($year >= 1620) && ($year <= 2000)) {
            $i = floor(($year - 1620) / 2);
            $f = (($year - 1620) / 2) - $i;  /* Fractional part of year */
            $dt = positionalAstronomy::$deltaTtab[(int)$i] + ((positionalAstronomy::$deltaTtab[(int)$i + 1] - positionalAstronomy::$deltaTtab[(int)$i]) * $f);
        } else {
            $t = ($year - 2000) / 100;
            if ($year < 948) {
                $dt = 2177 + (497 * $t) + (44.1 * $t * $t);
            } else {
                $dt = 102 + (102 * $t) + (25.3 * $t * $t);
                if (($year > 2000) && ($year < 2100)) {
                    $dt += 0.37 * ($year - 2100);
                }
            }
        }
        return $dt;
    }

    public static function equinox($year, $which)
    {
        /*  Initialise terms for mean equinox and solstices.  We
            have two sets: one for years prior to 1000 and a second
            for subsequent years.  */

        if ($year < 1000) {
            $JDE0tab = positionalAstronomy::$JDE0tab1000;
            $Y = $year / 1000;
        } else {
            $JDE0tab = positionalAstronomy::$JDE0tab2000;
            $Y = ($year - 2000) / 1000;
        }
        $JDE0 = $JDE0tab[$which][0] +
            ($JDE0tab[$which][1] * $Y) +
            ($JDE0tab[$which][2] * $Y * $Y) +
            ($JDE0tab[$which][3] * $Y * $Y * $Y) +
            ($JDE0tab[$which][4] * $Y * $Y * $Y * $Y);
        //debug.log.value += "JDE0 = " + JDE0 + "\n";
        $T = ($JDE0 - 2451545.0) / 36525;
        //debug.log.value += "T = " + T + "\n";
        $W = (35999.373 * $T) - 2.47;
        //debug.log.value += "W = " + W + "\n";
        $deltaL = 1 + (0.0334 * positionalAstronomy::dCos($W)) + (0.0007 * positionalAstronomy::dCos(2 * $W));
        //debug.log.value += "deltaL = " + deltaL + "\n";
        //Sum the periodic terms for time T
        $S = 0;
        for ($i = $j = 0; $i < 24; $i++) {
            $S += positionalAstronomy::$EquinoxpTerms[$j] * positionalAstronomy::dCos(positionalAstronomy::$EquinoxpTerms[$j + 1] + (positionalAstronomy::$EquinoxpTerms[$j + 2] * $T));
            $j += 3;
        }
        //debug.log.value += "S = " + S + "\n";
        //debug.log.value += "Corr = " + ((S * 0.00001) / deltaL) + "\n";
        $JDE = $JDE0 + (($S * 0.00001) / $deltaL);
        return $JDE;
    }

    /*  EQUINOX  --  Determine the Julian Ephemeris Day of an
                     equinox or solstice.  The "which" argument
                     selects the item to be computed:

                        0   March equinox
                        1   June solstice
                        2   September equinox
                        3   December solstice

    */

    //  Periodic terms to obtain true time

    public static function dCos($deg)
    {
        return cos(positionalAstronomy::dtr($deg));
    }

    public static function equationOfTime($jd)
    {
        $tau = ($jd - J2000) / JulianMillennium;
        //debug.log.value += "equationOfTime.  tau = " + tau + "\n";
        $L0 = 280.4664567 + (360007.6982779 * $tau) +
            (0.03032028 * $tau * $tau) +
            (($tau * $tau * $tau) / 49931) +
            (-(($tau * $tau * $tau * $tau) / 15300)) +
            (-(($tau * $tau * $tau * $tau * $tau) / 2000000));
        //debug.log.value += "L0 = " + L0 + "\n";
        $L0 = positionalAstronomy::fixangle($L0);
        //debug.log.value += "L0 = " + L0 + "\n";
        $alpha = positionalAstronomy::sunPos($jd)[10];
        //debug.log.value += "alpha = " + alpha + "\n";
        $deltaPsi = positionalAstronomy::nutation($jd)[0];
        //debug.log.value += "deltaPsi = " + deltaPsi + "\n";
        $epsilon = positionalAstronomy::obliqeq($jd) + positionalAstronomy::nutation($jd)[1];
        //debug.log.value += "epsilon = " + epsilon + "\n";
        $E = $L0 + (-0.0057183) + (-$alpha) + ($deltaPsi * positionalAstronomy::dCos($epsilon));
        //debug.log.value += "E = " + E + "\n";
        $E = $E - 20.0 * (floor($E / 20.0));
        //debug.log.value += "Efixed = " + E + "\n";
        $E = $E / (24 * 60);
        //debug.log.value += "Eday = " + E + "\n";
        return $E;
    }

    public static function sunPos($jd)
    {
        $T = ($jd - J2000) / JulianCentury;
        //debug.log.value += "Sunpos.  T = " + T + "\n";
        $T2 = $T * $T;
        $L0 = 280.46646 + (36000.76983 * $T) + (0.0003032 * $T2);
        //debug.log.value += "L0 = " + L0 + "\n";
        $L0 = positionalAstronomy::fixangle($L0);
        //debug.log.value += "L0 = " + L0 + "\n";
        $M = 357.52911 + (35999.05029 * $T) + (-0.0001537 * $T2);
        //debug.log.value += "M = " + M + "\n";
        $M = positionalAstronomy::fixangle($M);
        //debug.log.value += "M = " + M + "\n";
        $e = 0.016708634 + (-0.000042037 * $T) + (-0.0000001267 * $T2);
        //debug.log.value += "e = " + e + "\n";
        $C = ((1.914602 + (-0.004817 * $T) + (-0.000014 * $T2)) * positionalAstronomy::dSin($M)) +
            ((0.019993 - (0.000101 * $T)) * positionalAstronomy::dSin(2 * $M)) +
            (0.000289 * positionalAstronomy::dSin(3 * $M));
        //debug.log.value += "C = " + C + "\n";
        $sunLong = $L0 + $C;
        //debug.log.value += "sunLong = " + sunLong + "\n";
        $sunAnomaly = $M + $C;
        //debug.log.value += "sunAnomaly = " + sunAnomaly + "\n";
        $sunR = (1.000001018 * (1 - ($e * $e))) / (1 + ($e * positionalAstronomy::dCos($sunAnomaly)));
        //debug.log.value += "sunR = " + sunR + "\n";
        $Omega = 125.04 - (1934.136 * $T);
        //debug.log.value += "Omega = " + Omega + "\n";
        $Lambda = $sunLong + (-0.00569) + (-0.00478 * positionalAstronomy::dSin($Omega));
        //debug.log.value += "Lambda = " + Lambda + "\n";
        $epsilon0 = positionalAstronomy::obliqeq($jd);
        //debug.log.value += "epsilon0 = " + epsilon0 + "\n";
        $epsilon = $epsilon0 + (0.00256 * positionalAstronomy::dCos($Omega));
        //debug.log.value += "epsilon = " + epsilon + "\n";
        $Alpha = positionalAstronomy::rtd(atan2(positionalAstronomy::dCos($epsilon0) * positionalAstronomy::dSin($sunLong), positionalAstronomy::dCos($sunLong)));
        //debug.log.value += "Alpha = " + Alpha + "\n";
        $Alpha = positionalAstronomy::fixangle($Alpha);
        //debug.log.value += "Alpha = " + Alpha + "\n";
        $Delta = positionalAstronomy::rtd(asin(positionalAstronomy::dSin($epsilon0) * positionalAstronomy::dSin($sunLong)));
        //debug.log.value += "Delta = " + Delta + "\n";
        $AlphaApp = positionalAstronomy::rtd(atan2(positionalAstronomy::dCos($epsilon) * positionalAstronomy::dSin($Lambda), positionalAstronomy::dCos($Lambda)));
        //debug.log.value += "AlphaApp = " + AlphaApp + "\n";
        $AlphaApp = positionalAstronomy::fixangle($AlphaApp);
        //debug.log.value += "AlphaApp = " + AlphaApp + "\n";
        $DeltaApp = positionalAstronomy::rtd(asin(positionalAstronomy::dSin($epsilon) * positionalAstronomy::dSin($Lambda)));
        //debug.log.value += "DeltaApp = " + DeltaApp + "\n";

        return Array(                 //  Angular quantities are expressed in decimal degrees
            $L0,                           //  [0] Geometric mean longitude of the Sun
            $M,                            //  [1] Mean anomaly of the Sun
            $e,                            //  [2] Eccentricity of the Earth's orbit
            $C,                            //  [3] Sun's equation of the Centre
            $sunLong,                      //  [4] Sun's true longitude
            $sunAnomaly,                   //  [5] Sun's true anomaly
            $sunR,                         //  [6] Sun's radius vector in AU
            $Lambda,                       //  [7] Sun's apparent longitude at true equinox of the date
            $Alpha,                        //  [8] Sun's true right ascension
            $Delta,                        //  [9] Sun's true declination
            $AlphaApp,                     // [10] Sun's apparent right ascension
            $DeltaApp                      // [11] Sun's apparent declination
        );
    }

    public static function dSin($deg)
    {
        return sin(positionalAstronomy::dtr($deg));
    }

    /*  SUNPOS  --  Position of the Sun.  Please see the comments
                    on the return statement at the end of this function
                    which describe the array it returns.  We return
                    intermediate values because they are useful in a
                    variety of other contexts.  */

    public static function nutation($jDate)
    {
        $t = ($jDate - 2451545.0) / 36525.0;
        $ta = Array();
        $dp = 0;
        $de = 0;
        $t3 = $t * ($t2 = $t * $t);
        /* Calculate angles.  The correspondence between the elements
           of our array and the terms cited in Meeus are:
           ta[0] = D  ta[0] = M  ta[2] = M'  ta[3] = F  ta[4] = \Omega
        */
        $ta[] = positionalAstronomy::dtr(297.850363 + 445267.11148 * $t - 0.0019142 * $t2 + $t3 / 189474.0);
        $ta[] = positionalAstronomy::dtr(357.52772 + 35999.05034 * $t - 0.0001603 * $t2 - $t3 / 300000.0);
        $ta[] = positionalAstronomy::dtr(134.96298 + 477198.867398 * $t + 0.0086972 * $t2 + $t3 / 56250.0);
        $ta[] = positionalAstronomy::dtr(93.27191 + 483202.017538 * $t - 0.0036825 * $t2 + $t3 / 327270);
        $ta[] = positionalAstronomy::dtr(125.04452 - 1934.136261 * $t + 0.0020708 * $t2 + $t3 / 450000.0);
        /* Range reduce the angles in case the sine and cosine functions
           don't do it as accurately or quickly. */
        for ($i = 0; $i < 5; $i++) {
            $ta[$i] = positionalAstronomy::fixangr($ta[$i]);
        }
        $to10 = $t / 10.0;
        for ($i = 0; $i < 63; $i++) {
            $ang = 0;
            for ($j = 0; $j < 5; $j++) {
                if (positionalAstronomy::$nutArgMult[($i * 5) + $j] != 0) {
                    $ang += positionalAstronomy::$nutArgMult[($i * 5) + $j] * $ta[$j];
                }
            }
            $dp += (positionalAstronomy::$nutArgCoeff[($i * 4) + 0] + positionalAstronomy::$nutArgCoeff[($i * 4) + 1] * $to10) * sin($ang);
            $de += (positionalAstronomy::$nutArgCoeff[($i * 4) + 2] + positionalAstronomy::$nutArgCoeff[($i * 4) + 3] * $to10) * cos($ang);
        }
        /* Return the result, converting from ten thousandths of arc
           seconds to radians in the process. */
        $deltaPsi = $dp / (3600.0 * 10000.0);
        $deltaEpsilon = $de / (3600.0 * 10000.0);

        return Array($deltaPsi, $deltaEpsilon);
    }

    /*  EQUATIONOFTIME  --  Compute equation of time for a given moment.
                            Returns the equation of time as a fraction of
                            a day.  */

    public static function fixAngr($angRad)
    {
        return $angRad - (2 * M_PI) * (floor($angRad / (2 * M_PI)));
    }
} 