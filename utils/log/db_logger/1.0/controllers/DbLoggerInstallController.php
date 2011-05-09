<?php
require_once dirname(__FILE__).'/../utils/dao_generator.php';

/**
 * The controller used in the DbLogger install process.
 * 
 * @Component
 */
class DbLoggerInstallController extends Controller {
	
	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Displays the first install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
				
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep1.php", $this);
		$this->template->draw();
	}

	/**
	 * Skips the install process.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function skip($selfedit = "false") {
		InstallUtils::continueInstall($selfedit == "true");
	}

	protected $tableName;
	
	/**
	 * Displays the second install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function configure($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		// Let's start by performing basic checks about the instances we assume to exist.
		if (!$this->moufManager->instanceExists("dbConnection")) {
			$this->displayErrorMsg("The TDBM install process assumes your database connection instance is already created, and that the name of this instance is 'dbConnection'. Could not find the 'dbConnection' instance.");
			return;
		}
		
		$this->tableName = "logs";
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep2.php", $this);
		$this->template->draw();
	}
	
	/**
	 * This action generates the DbLogger instance, and creates the table. 
	 * 
	 * @Action
	 * @param string $tablename
	 * @param string $selfedit
	 */
	public function install($tablename, $selfedit="false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->createTable($tablename, $selfedit);
				
		if (!$this->moufManager->instanceExists("dbLogger")) {
			$this->moufManager->declareComponent("dbLogger", "DbLogger");
			$this->moufManager->bindComponent("dbLogger", "dbConnection", "dbConnection");
		}
		
		$this->moufManager->rewriteMouf();
		
		InstallUtils::continueInstall($selfedit == "true");
	}
	
	protected $errorMsg;
	
	private function displayErrorMsg($msg) {
		$this->errorMsg = $msg;
		$this->template->addContentFile(dirname(__FILE__)."/../views/installError.php", $this);
		$this->template->draw();
	}
	
	protected function createTable($tablename, $selfedit) {
		
		var_dump(DB_ConnectionProxy::getLocalProxy()->getListOfTables(true));
		
	}
	
}