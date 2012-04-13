<?php
require_once '../../../../../Mouf.php';
require_once(dirname(__FILE__)."/../../../../../mouf/reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../../../../../mouf/Moufspector.php");

// $data = $_GET['data'];
// $dataValue = $_GET['dataValue'];

// switch ($data) {
// 	case 'beanData':
// 	;
// 	break;
// 	case 'daoList':
// 		;
// 	break;
// 	case 'fieldType':
// 		;
// 	break;
// }

$class = MoufReflectionProxy::getClass("UserDAO", false);
$method = $class->getMethod("getById");
$returnClass = $method->getAnnotations('return');

$beanClass = MoufReflectionProxy::getClass($returnClass[0], false);
$methods = $beanClass->getMethodsByPattern("[gs]et");

foreach ($methods as $method) {
	echo "<br/>".$method->getName();
	$returnAnnotation = $method->getAnnotations('return');
	if (count($returnAnnotation)){
		$returnType = $returnAnnotation[0];
		$returnType = explode(" ", $returnType);
		$returnType = $returnType[0];
		echo " -- ".$returnType; 
	}
}