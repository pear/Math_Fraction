<?php

/*
 * To test Math_Fraction and Math_FractionOp
 * $Id$
 */

require_once "Math/FractionOp.php";

$a = new Math_Fraction(2, 6);
$b = new Math_Fraction(3, 8);

// creating fraction from float
$c = new Math_Fraction(3.14159265358979323846);

echo "a = ".$a->toString()."\n";
echo "b = ".$b->toString()."\n";

echo "c = ".$c->toString()."\n";

$n = Math_FractionOp::compare($a, $b);
echo "compare(a, b) = ".$n."\n";

$n = Math_FractionOp::add($a, $b);
echo "add(a, b) = a + b = ".$n->toString()."\n";

$n = Math_FractionOp::sub($a, $b);
echo "sub(a, b) = a - b = ".$n->toString()."\n";

$n = Math_FractionOp::sub($b, $a);
echo "sub(b, a) = b - a: ".$n->toString()."\n";

$n = Math_FractionOp::mult($a, $b);
echo "mult(a, b) = a * b = ".$n->toString()."\n";

$n = Math_FractionOp::mult($a, $b, false);
echo "mult(a, b, false) = a * b = ".$n->toString()." - without simplification \n";

$n = Math_FractionOp::div($a, $b);
echo "div(a, b) = a / b = ".$n->toString()."\n";

$n = Math_FractionOp::div($b, $a);
echo "div(b, a) = b / a = ".$n->toString()."\n";

$n = Math_FractionOp::simplify($a);
echo "simplify(a) = ".$n->toString()."\n";

$n = Math_FractionOp::reciprocal($a);
echo "reciprocal(a) = ".$n->toString()."\n";

$n = $a->toFloat();
echo "a->toFloat() = ".$n."\n";
?>