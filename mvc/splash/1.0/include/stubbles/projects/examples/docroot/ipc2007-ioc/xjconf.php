<?php
require '../bootstrap-stubbles.php';
stubClassLoader::load('net::stubbles::util::xjconf::xjconf');
stubClassLoader::load('net::stubbles::util::xjconf::xjconfReal');

require 'interfaces.php';
require 'classes.php';

$xjconf = new XJConfFacade();
$xjconf->addDefinition('xjconf-defines.xml');
$xjconf->parse('xjconf-config.xml');
$bmw = $xjconf->getConfigValue('car');
$bmw->moveForward(50);
?>