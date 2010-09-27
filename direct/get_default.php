<?php
/**
 * Returns the default value for a class property, as a serialized PHP string. 
 */

require_once '../../Mouf.php';
require_once 'utils/check_rights.php';

// FIXME; moyen secure ça!
$instance = new $_REQUEST["class"]();
$property = $_REQUEST["property"];

echo serialize($instance->$property);

?>