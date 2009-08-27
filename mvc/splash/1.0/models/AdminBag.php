<?php
class AdminBag {

	public $controller;
	public $action;
	public $args;
	public $table_infos;
	public $argsArray;

	private static $adminbag;

	/**
	 * Enter description here...
	 *
	 * @return AdminBag
	 */
	public static function getInstance() {
		if(!self::$adminbag){
			self::$adminbag = new AdminBag();
		}
		return self::$adminbag;
	}

	public  function getTableInfos($table) {
		if(!isset($this->table_infos[$table]['infos'])) {
			DBLib::getTableInfos($table);
			DBLib::getTableConstraints($table);
		}
		return $this->table_infos[$table];
	}

	public static function getUserGroup(){
		$groups = DBM_Object::getObjects('groups', "label='".DB_USER_GROUP_LABEL."'");
		if (count($groups)==1){
			return $groups[0]->id;
		}else throw new Exception("Error retrieving user group (either no group or more then one with label".DB_USER_GROUP_LABEL.")");

	}


	public static function StoreRedirectUrl() {
		$redirect_uri = $_SERVER['REDIRECT_URL'];
		$pos = strpos($redirect_uri, ROOT_URL);
		$action = substr($redirect_uri, $pos+strlen(ROOT_URL));

		$array = split("/", $action);
		$adminbag = AdminBag::getInstance();
		$adminbag->controller = $array[0];
		$adminbag->action=$array[1];
		$adminbag->args = array();

		array_shift($array);
		array_shift($array);

		$adminbag->argsArray = $array;

		$i=0;
		foreach ($array as $arg) {
			$adminbag->args["arg$i"]=$arg;
			$i++;
		}
	}
}
?>