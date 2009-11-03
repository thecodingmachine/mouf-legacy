<?php 
require_once dirname(__FILE__).'/../../../../../Mouf.php';

$conn = new DB_MySqlConnection();
$conn->host = "localhost";
$conn->dbname = "test";
$conn->user = "root";
$conn->connect();

TDBM_Object::connect($conn);
$test = TDBM_Object::getObjects("test");

//var_dump($conn->getAll("SELECT * FROM users"));

//var_dump($conn->getOne("SELECT count(1) FROM users"));

var_dump($test);

?>