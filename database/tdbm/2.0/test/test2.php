<?php 
require_once dirname(__FILE__).'/../../../../../Mouf.php';

$tdbm = Mouf::getTdbm();

//$users = $tdbm->getObjects("user", new TDBM_EqualFilter("site", "label","Coca-cola.fr"));

$users = $tdbm->getObjects("user", new TDBM_EqualFilter("user", "login","publisher"));
foreach ($users as $user) {
	echo $user->login." ";
}

$publisher = $tdbm->getObjects("publisher", $users[0]);
var_dump($publisher);
?>