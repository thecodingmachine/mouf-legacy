<?php
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::ioc::ioc');

require 'interfaces.php';
require 'classes.php';

$binder = new stubBinder();
$binder->bind('Tire')->to('Goodyear');
$binder->bind('Person')->to('Schst');
$binder->bind('Engine')->to('TwoLitresEngine');

$injector = $binder->getInjector();
$bmw = $injector->getInstance('BMW');
$bmw->moveForward(50);
?>