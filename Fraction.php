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

include_once 'PEAR.php';

/**
 * Math_Fraction: class to represent and manipulate fractions (n = a/b)
 *
 *
 * @author  Kouber Saparev <kouber@php.net>
 * @version 0.3.0
 * @access  public
 * @package Math_Fraction
 */
class Math_Fraction {

    /**
     * The numerator of the fraction
     *
     * @var int
     * @access  private
     */
    var $_num;

    /**
     * The denominator of the fraction
     *
     * @var int
     * @access  private
     */
    var $_den;
    
    /**
     * Constructor for Math_Fraction
     * 
     * @param mixed $num Integer for the Numerator or a Float that the fraction will be built from.
     * @param int $den Denominator
     * @return object Math_Fraction
     * @access public
     */
    function Math_Fraction($num, $den = null)
    {   
        if (is_float($num)) {
            // the fraction is built from a float
            // signature = (float)
            $fr =& Math_FractionOp::floatToFraction($num);

            $this->_num =& $fr->getNum();
            $this->_den =& $fr->getDen();
        } else {
            // classical construction with numerator and denominator
            // signature = (int, int)
            if (is_null($den)) {
                // invalid signature = (int)
                return PEAR::raiseError('Denominator missing.');
            }

            $num = intval($num);
            $den = intval($den);

            if (!$den) {
                return PEAR::raiseError('Denominator must not be zero.');
            }

            $this->_num = intval($num);
            $this->_den = intval($den);
        }
    }
    
    /**
     * Returns the numerator of the fraction
     *
     * @return int
     * @access public
     */
    function getNum()
    {
        return $this->_num;
    }

    /**
     * Returns the denominator of the fraction
     * @return int
     * @access public
     */
    function getDen()
    {
        return $this->_den;
    }

    /**
     * Float evaluation of the fraction
     *
     * @return float
     * @access public
     */
    function toFloat()
    {
        $n = $this->getNum();
        $d = $this->getDen();
        return floatval($n / $d);
    }

    /**
     * String representation of the fraction
     *
     * @return string
     * @access public
     */
    function toString()
    {
        $n = $this->getNum();
        $d = $this->getDen();
        return "$n/$d";
    }
}
?>