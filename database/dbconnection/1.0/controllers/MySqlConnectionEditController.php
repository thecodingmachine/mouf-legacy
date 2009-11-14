<?php

/**
 * The controller to edit MySql connections and to test them!
 * So cool!
 * 
 * @Component
 */
class MySqlConnectionEditController extends AbstractMoufInstanceController {
	
		
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * //@Admin
	 */
	public function defaultAction($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/mysqlEdit.php", $this);
		$this->template->draw();
	}
}

?>