<?php
require 'interfaces.php';
require 'classes.php';

$tire   = new Goodyear();
$engine = new TwoLitresEngine();
$schst  = new Schst();

$bmw    = new BMW($engine, $tire);
$bmw->setDriver($schst);

$bmw->moveForward(50);
?>