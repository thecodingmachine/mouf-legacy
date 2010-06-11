<?php
require_once dirname(__FILE__)."/../MoufConfigManager.php";

/**
 * The controller managing the config.php file.
 *
 * @Component
 */
class ConfigController extends Controller {

	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The active ConfigManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $configManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	

	/**
	 * The list of constants defined.
	 *
	 * @var array<string, string>
	 */
	protected $constantsList;
		
	/**
	 * Displays the list of defined parameters in config.php
	 * 
	 * @Action
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 * @param string $validation The validation message to display (either null, or confirmok).
	 */
	public function defaultAction($selfedit = "false", $validation = null) {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		//$this->constantsList = $this->moufManager->getConfigManager()->getDefinedConstants();
		$this->constantsList = $this->moufManager->getConfigManager()->getMergedConstants();
		
		$this->template->addContentFile("views/constants/displayConstantsList.php", $this);
		$this->template->draw();
	}
	
	/**
	 * The action to save the configuration.
	 *
	 * @Action
	 */
	public function saveConfig($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->configManager = $this->moufManager->getConfigManager();
		$this->constantsList = $this->configManager->getMergedConstants();
		
		$constants = array();
		foreach ($this->constantsList as $key=>$def) {
			$constants[$key] = get($key);
		}
		$this->configManager->setDefinedConstants($constants);
		
		header("Location: .?selfedit=".$selfedit);
	}
	
	protected $name;
	protected $defaultvalue;
	protected $comment;
	
	/**
	 * Displays the screen to register a constant definition.
	 *
	 * @Action
	 * @param string $name
	 * @param string $selfedit
	 */
	public function register($name = null, $defaultvalue = null, $comment = null, $selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		$this->configManager = $this->moufManager->getConfigManager();
		
		$this->name = $name;
				
		$this->defaultvalue = $defaultvalue;
		$this->comment = $comment;
		
		if ($name != null) {
			$def = $this->configManager->getConstantDefinition($name);
			if ($def != null) {
				if ($this->comment == null) {
					$this->comment = $def['comment'];
				}
				if ($this->defaultvalue == null) {
					$this->defaultvalue = $def['defaultValue'];
				}
			}
		}
		
		// TODO: manage type!
		//$this->type = $comment;
		
		$this->template->addContentFile("views/constants/registerConstant.php", $this);
		$this->template->draw();
	}

	/**
	 * Actually saves the new constant declared.
	 *
	 * @Action
	 * @param string $name
	 * @param string $defaultvalue
	 * @param string $comment
	 * @param string $selfedit
	 */
	public function registerConstant($name, $defaultvalue, $comment, $selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		$this->configManager = $this->moufManager->getConfigManager();
		
		$this->configManager->registerConstant($name, "string", $defaultvalue, $comment);
		$this->moufManager->rewriteMouf();
		
		header("Location: .?selfedit=".$selfedit);
	}

	/**
	 * Deletes a constant.
	 *
	 * @Action
	 * @param string $name
	 */
	public function deleteConstant($name, $selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		$this->configManager = $this->moufManager->getConfigManager();
		
		$this->configManager->unregisterConstant($name);
		
		$this->moufManager->rewriteMouf();
		
		header("Location: .?selfedit=".$selfedit);
	}
}
?>