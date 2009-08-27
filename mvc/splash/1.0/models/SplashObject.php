<?php
abstract class SplashObject {
	public $db_object;

	public function __get($var) {
		return $this->db_object->$var;
	}

	public function __set($var,$value) {
		$this->db_object->$var=$value;
	}
	public function __call($name,$args) {
		$this->db_object->$name($args);
	}
}
?>