<?php

require_once(dirname(__FILE__)."/../reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../Moufspector.php");
require_once(dirname(__FILE__)."/../annotations/OneOfAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/OneOfTextAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/varAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/paramAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/ExtendedActionAnnotation.php");
require_once(dirname(__FILE__)."/../CanvasWriter.php");

/**
 * The controller allowing access to the Mouf framework.
 *
 * @Component
 */
class MoufController extends Controller {

	public $instanceName;
	public $className;
	public $properties;
	//public $instance;
	public $reflectionClass;
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
	 * Array of all instances sorted by package and by class.
	 *
	 * @var array<string, array<string, string>>
	 */
	public $instancesByPackage;
	
	/**
	 * Array of all instances that are bound to classes that no longer exist.
	 * 
	 * @var array<string, string> key: instance name, value: class name.
	 */
	public $inErrorInstances;
	
	/**
	 * Redirects the user to the right controller.
	 * This canb e the detail controller, or any other controller, depending on the @ExtendedAction annotation.
	 * 
	 * @Action
	 * @Logged
	 * @param string $name the name of the component to display
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function displayComponent($name, $selfedit = "false") {
		$this->instanceName = $name;
		$this->selfedit = $selfedit;
		/*$this->instance = MoufManager::getMoufManager()->getInstance($name);
		$this->className = MoufManager::getMoufManager()->getInstanceType($this->instanceName);*/
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->className = $this->moufManager->getInstanceType($this->instanceName);		
		$this->reflectionClass = MoufReflectionProxy::getClass($this->className, $selfedit=="true");
		$extendedActions = $this->reflectionClass->getAnnotations("ExtendedAction");
		$destinationUrl = ROOT_URL."mouf/instance/";
		if (!empty($extendedActions)) {
			foreach ($extendedActions as $extendedAction) {
				if ($extendedAction->isDefault()) {
					$destinationUrl = ROOT_URL.$extendedAction->getUrl();
					break;
				}
			}
		}
		
		// Note: performing a redirect is not optimal as it requires a reload of the page!
		header("Location: ".$destinationUrl."?name=".urlencode($name)."&selfedit=".urlencode($selfedit));
		exit;		
	}
	
	/**
	 * Lists all the components available, sorted by "package" (directory of the class).
	 * 
	 * @Action
	 * @Logged
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;

		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$enhancedComponentsList = MoufReflectionProxy::getEnhancedComponentsList($selfedit=="true");
		// Let's create a list by declaration file:
		$componentsByFile = array();
		foreach ($enhancedComponentsList as $name=>$componentDetails) {
			$componentsByFile[$componentDetails["filename"]][] = $name;
		}
		// Now, let's remove the part of the file that is common to every file.
		// The aim is to remove the C:/Program Files/.... or /var/www/...
		$first = true;
		$common = "";
		foreach ($componentsByFile as $key=>$name) {
			if ($first) {
				$common = $key;
				$first = false;
				continue;
			} else {
				if (strpos($key, $common) === 0) {
					continue;
				} else {
					// Let's find what part of the $common string is common to the key.
					while (strlen($common)!=0) {
						$common = substr($common, 0, -1);
						if (strpos($key, $common) === 0) {
							break;
						}
					}
				}
			}
		}
		// Now that we have the common part, let's remove it, and let's get only the directory name (remove the file part)
		$componentsByShortFile = array();
		foreach ($componentsByFile as $key=>$names) {
			$packageName = dirname(substr($key, strlen($common)));
			if (!isset($componentsByShortFile[$packageName])) {
				$componentsByShortFile[$packageName] = $names;
			} else {
				$componentsByShortFile[$packageName] = array_merge($componentsByShortFile[$packageName], $names);
			}
		}
		
		// Now, let's sort all this by key.
		ksort($componentsByShortFile);
		
		// We have a sorted components list.
		// Now, for each of these, let's find the instance that match...
		$instanceList = $this->moufManager->getInstancesList();
		// Let's revert the instance list.
		$instanceListByClass = array();
		foreach ($instanceList as $instanceName=>$className) {
			$instanceListByClass[$className][] = $instanceName;
		}
		
		// The instances are bound to classes that no longer exist:
		$this->inErrorInstances = $instanceList;
		
		// type: array<package, array<class, instance>>
		$instancesByPackage = array();
		foreach ($componentsByShortFile as $package=>$classes) {
			foreach ($classes as $class) {
				if (isset($instanceListByClass[$class])) {
					$instancesByPackage[$package][$class] = $instanceListByClass[$class];
					foreach ($instanceListByClass[$class] as $instance) {
						unset($this->inErrorInstances[$instance]);
					}
				}
			}
		}
		
		$this->instancesByPackage = $instancesByPackage;
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/listComponentsByDirectory.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Lists all the components available, ordered by creation date, in order to edit them.
	 * 
	 * @Action
	 * @Logged
	 */
	public function instancesByDate($selfedit = "false") {
		$this->selfedit = $selfedit;

		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/listComponents.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Displays the screen allowing to create new instances.
	 *
	 * @Action
	 * @Logged
	 */
	public function newInstance($selfedit = "false", $instanceName=null, $instanceClass=null) {
		//$componentsList = Moufspector::getComponentsList();
		$this->selfedit = $selfedit;
		$componentsList = MoufReflectionProxy::getComponentsList($selfedit=="true");
		sort($componentsList);
		
		$template = $this->template;
		$template->addContentFunction(array($this, "displayNewInstanceScreen"), $componentsList, $selfedit, $instanceName, $instanceClass);
		//$template->addContentFile(dirname(__FILE__)."/../views/displayNewInstance.php", $this);
		$template->draw();	
	}
	
	/**
	 * Displays the new component view
	 *
	 */
	public function displayNewInstanceScreen($componentsList, $selfedit, $instanceName, $instanceClass) {
		include(dirname(__FILE__)."/../views/displayNewInstance.php");
	}
	
	/**
	 * The action that creates a new component instance.
	 *
	 * @Action
	 * @Logged
	 * @param string $instanceName The name of the instance to create
	 * @param string $instanceClass The class of the component to create
	 */
	public function createComponent($instanceName, $instanceClass, $selfedit) {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		
		$this->moufManager->declareComponent($instanceName, $instanceClass);
		$this->moufManager->rewriteMouf();
		
		// Redirect to the display component page:
		$this->displayComponent($instanceName, $selfedit);
	}
	
}
?>