<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Kouber Saparev <kouber@php.net>                             |
// +----------------------------------------------------------------------+
//
// $Id$

include_once 'Math/Fraction.php';

/**
 * Math_FractionOp: static class to operate on Math_Fraction objects
 *
 * @author  Kouber Saparev <kouber@php.net>
 * @version 0.4.1
 * @access  public
 * @package Math_Fraction
 */
class Math_FractionOp {

    /**
     * Checks if a given object is an instance of PEAR::Math_Fraction
     *
     * @static
     * @return boolean
     * @access public
     */
    function isFraction($n)
    {
        if (function_exists('is_a')) {
            return is_a($n, 'math_fraction');
        } else {
            return (strtolower(get_class($n)) == 'math_fraction' 
                    || is_subclass_of($n, 'math_fraction'));
        }
    }

    /**
     * Compares two fractions.
     * if $n1 > $n2, returns 1,
     * if $n1 == $n2, returns 0,
     * if $n1 < $n2, returns -1
     *
     * @static
     * @param object $n1 Math_Fraction
     * @param object $n2 Math_Fraction
     * @return int
     * @access public
     */
    function compare($n1, $n2) 
    {
        if (!Math_FractionOp::isFraction($n1) 
            || !Math_FractionOp::isFraction($n2)) {
            return Math_FractionOp::raiseError('Both arguments must be PEAR::Math_Fraction objects');
        } else {
            $num1 = $n1->getNum();
            $den1 = $n1->getDen();

            $num2 = $n2->getNum();
            $den2 = $n2->getDen();

            $lcm = Math_FractionOp::lcm($den1, $den2);

            $f1 = $num1 * $lcm/$den1;
            $f2 = $num2 * $lcm/$den2;

            if ($f1 < $f2) {
                return -1;
            } else {
                return intval($f1 > $f2);
            }
        }
    }

    /**
     * Returns the sum of two fractions: n = n1 + n2
     *
     * @static
     * @param object $n1 Math_Fraction
     * @param object $n2 Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function add($n1, $n2, $return_simplified = true) 
    {
        if (!Math_FractionOp::isFraction($n1) 
            || !Math_FractionOp::isFraction($n2)) {
            return Math_FractionOp::raiseError('Both arguments must be PEAR::Math_Fraction objects');
        } else {
            $den1 = $n1->getDen();
            $den2 = $n2->getDen();
            $lcm = Math_FractionOp::lcm($den1, $den2);
            $num = $n1->getNum() * $lcm/$den1 + $n2->getNum() * $lcm/$den2;
            $f = new Math_Fraction(intval($num), $lcm);
            if ($return_simplified) {
                return Math_FractionOp::simplify($f);
            } else {
                return $f;
            }
        }
    }

    /**
     * Returns the subtraction of two fractions: n = n1 - n2
     *
     * @static
     * @param object $n1 Math_Fraction
     * @param object $n2 Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function sub($n1, $n2, $return_simplified = true) 
    {
        if (!Math_FractionOp::isFraction($n1) 
            || !Math_FractionOp::isFraction($n2)) {
            return Math_FractionOp::raiseError('Both arguments must be PEAR::Math_Fraction objects');
        } else {
            return Math_FractionOp::add($n1, new Math_Fraction($n2->getNum()*-1, $n2->getDen()), $return_simplified);
        }
    }

    /**
     * Returns the product of two fractions: n = n1 * n2
     *
     * @static
     * @param object $n1 Math_Fraction
     * @param object $n2 Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function mult($n1, $n2, $return_simplified = true) 
    {
        if (!Math_FractionOp::isFraction($n1) 
            || !Math_FractionOp::isFraction($n2)) {
            return Math_FractionOp::raiseError('Both arguments must be PEAR::Math_Fraction objects');
        } else {
            $num = $n1->getNum() * $n2->getNum();
            $den = $n1->getDen() * $n2->getDen();
            $f = new Math_Fraction(intval($num), $den);
            if ($return_simplified) {
                return Math_FractionOp::simplify($f);
            } else {
                return $f;
            }
        }
    }

    /**
     * Returns the quotient of two fractions: n = n1 / n2
     *
     * @static
     * @param object $n1 Math_Fraction
     * @param object $n2 Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function div($n1, $n2, $return_simplified = true) 
    {
        if (!Math_FractionOp::isFraction($n1) 
            || !Math_FractionOp::isFraction($n2)) {
            return Math_FractionOp::raiseError('Both arguments must be PEAR::Math_Fraction objects');
        } else {
            return Math_FractionOp::mult($n1, Math_FractionOp::reciprocal($n2), $return_simplified);
        }
    }

    /**
     * Returns the greatest common divisor of two integers.
     *
     * @static
     * @param int $n1
     * @param int $n2
     * @return int
     * @access public
     */
    function gcd($n1, $n2) 
    {
        $n1 = intval($n1);
        $n2 = intval($n2);

        $n1 = abs($n1);
        $n2 = abs($n2);

        if ($n1 < $n2) {
            $t = $n1;
            $n1 = $n2;
            $n2 = $t;
        }

        while ($n2 != 0){
            $t = $n1 % $n2;
            $n1 = $n2;
            $n2 = $t;
        }

        return intval($n1);
    }

    /**
     * Returns the least common multiple of two integers.
     *
     * @static
     * @param int $n1
     * @param int $n2
     * @return int
     * @access public
     */
    function lcm($n1, $n2)
    {
        $n1 = intval($n1);
        $n2 = intval($n2);

        $n1 = abs($n1);
        $n2 = abs($n2);

        return intval(($n1 * $n2) / Math_FractionOp::gcd($n1, $n2));
    }

    /**
     * Returns the reciprocal value of a fraction: n = 1/n
     *
     * @static
     * @param object $n Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function reciprocal($n) 
    {
        if (!Math_FractionOp::isFraction($n)) {
            return Math_FractionOp::raiseError('Argument must be PEAR::Math_Fraction object');
        } else {
            $num = $n->getNum();
            $den = $n->getDen();
            return new Math_Fraction($den, $num);
        }
    }

    /**
     * Returns the simplified value (reduction) of a fraction.
     *
     * @static
     * @param object $n Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function &simplify(&$n)
    {
        if (!Math_FractionOp::isFraction($n)) {
            return Math_FractionOp::raiseError('Argument must be PEAR::Math_Fraction object');
        } else {
            $num = $n->getNum();
            $den = $n->getDen();
            $gcd = Math_FractionOp::gcd($num, $den);
            if ($gcd > 1) {
                return new Math_Fraction($num/$gcd, $den/$gcd);
            } else {
                return $n;
            }
        }
    }

    /**
     * An alias of the Math_FractionOp::simplify() method.
     *
     * @static
     * @param object $n Math_Fraction
     * @return object Math_Fraction
     * @access public
     */
    function &reduce(&$n)
    {
        return Math_FractionOp::simplify($n);
    }

    /**
     * Converts float to fraction and try to keep the maximal possible precision.
     *
     * @static
     * @param float $f
     * @return object Math_Fraction
     * @access public
     */
    function floatToFraction($f)
    {
        $f = floatval($f);

        // keep the original sign so that the numerator could be converted later
        $is_negative = ($f < 0);
        if ($is_negative) {
            $f *= -1;
        }

        // get the part before the floating point
        $int = floor($f);

        // make the float belonging to the interval [0, 1)
        $flt = $f - $int;

        // strip the zero and the floating point
        $flt = substr($flt, 2);

        // try to get an integer for the numerator
        do {
            $len = strlen($flt);
            $val = $int * pow(10, $len) + $flt;
            $flt = substr($flt, 0, -1);
        } while ($val > intval($val));

        if ($is_negative) {
            $val *= -1;
        }

        $num = intval($val);
        $den = pow(10, $len);

        return new Math_Fraction($num, $den);
    }

    /**
     * Converts string to fraction.
     *
     * @static
     * @param string $str
     * @return object Math_Fraction
     * @access public
     */
    function stringToFraction($str)
    {
        if (preg_match('#^(-)? *?(\d+) *?/ *?(-)? *?(\d+)$#', trim($str), $m)) {
            $num =& $m[2];
            $den =& $m[4];

            if ($m[1] xor $m[3]) {
                // there is one "-" sign => the fraction is negative
                $num *= -1;
            }

            if (!$den) {
                return Math_FractionOp::raiseError('Denominator must not be zero.');
            } else {
                return new Math_Fraction($num, $den);
            }
        } else {
            return Math_FractionOp::raiseError('Invalid fraction.');
        }
    }

    /**
     * An error capturing function.
     *
     * @static
     * @param string $str
     * @return object PEAR::raiseError()
     * @access public
     */
    function raiseError($str)
    {
        include_once 'PEAR.php';
        return PEAR::raiseError($str);
    }
}
?>