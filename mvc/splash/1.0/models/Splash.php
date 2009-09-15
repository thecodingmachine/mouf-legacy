<?php
// Loading common configuration for the application
require_once dirname(__FILE__)."/../load.php";

/**
 * The Splash component is the root of the Splash framework.
 * It is in charge of binding an Url to a Controller.
 * There is one and only one instance of Splash per web application.
 * The name of the instance MUST be "splash".
 * 
 * The Splash component has several ways to bind an URL to a Controller.
 * It can do so:
 * - using the instance name of a controller that has been instanciated with Mouf.
 * For instance, if a controller has an instance name that is "myController", then
 * the http://[myserver]/[mywebapp]/myController URL will lead to the default action
 * of that controller.
 * The http://[myserver]/[mywebapp]/myController/myAction URL will lead to the myAction action
 * of that controller. 
 * 
 * @Component
 * @RequiredInstance "splash"
 */
class Splash {

	/**
	 * The logger used by Splash
	 *
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $log;
	
	/**
	 * The default template used by Splash (for displaying error pages, etc...)
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $defaultTemplate;
	
	/**
	 * Set to "true" if the server supports HTTPS.
	 * This can be used by various plugins (especially the RequiresHttps annotation).
	 *
	 * @var boolean
	 */
	public $supportsHttps;
	
	/**
	 * The controller in the Url (this should be the first "directory" of the URL after the webapp.
	 *
	 * @var string
	 */
	private $controller;

	/**
	 * The action in the Url (this should be the second "directory" of the URL after the webapp.
	 *
	 * @var string
	 */
	private $action;
	
	/**
	 * An array containing all the directories after the action.
	 *
	 * @var string
	 */
	private $args;
	
	/**
	 * True if the URL analyze has been already performed.
	 *
	 * @var boolean
	 */
	private $analyzeDone;
	
	/**
	 * The beginning of the URL before Splash is activated. This is basically the webapp directory name.
	 *
	 * @var string
	 */
	private $splashUrlPrefix;
	
	/**
	 * Analyze the URL and fills the "controller", "action" and "args" variables.
	 *
	 */
	private function analyze() {
		$redirect_uri = $_SERVER['REDIRECT_URL'];
				
		$pos = strpos($redirect_uri, $this->splashUrlPrefix);
		
		if ($pos === FALSE) {
			throw new Exception('Error: the prefix of the web application "'.$this->splashUrlPrefix.'" was not found in the URL. The application must be misconfigured.');
		}
		
		$action = substr($redirect_uri, $pos+strlen($this->splashUrlPrefix));

		$array = explode("/", $action);
		
		$this->controller = $array[0];
		if (isset($array[1])) {
			$this->action = $array[1];
		}
		$this->args = array();

		array_shift($array);
		array_shift($array);

		$this->args = $array;

		$this->analyzeDone = true;
	}
	
	/**
	 * Returns the instance of the destination controller, or null if the controller was not found.
	 *
	 * @return Controller
	 */
	public function getControllerInstance() {
		if (!$this->analyzeDone) {
			$this->analyze();
		}
		try {
			return MoufManager::getMoufManager()->getInstance($this->controller);
		} catch (MoufException $e) {
	throw $e;
			return null;
		}
	}
	
	/**
	 * Returns the controller string, or null if the user wants the root directory.
	 *
	 * @return string
	 */
	public function getControllerName() {
		if (!$this->analyzeDone) {
			$this->analyze();
		}
		
		return $this->controller;
	}
	
	/**
	 * Returns the action, or null if none is provided by the user.
	 *
	 * @return string
	 */
	public function getAction() {
		if (!$this->analyzeDone) {
			$this->analyze();
		}
		
		return $this->action;
	}
	
	/**
	 * Returns the args associated to the URL.
	 * The args is the list of directories, as a list of strings, after the action
	 *
	 * @return array<string>
	 */
	public function getArgs() {
		return $this->args;
	}
	

	/**
	 * Route the user to the right controller according to the URL.
	 *
	 */
	public function route($splashUrlPrefix) {
		$this->splashUrlPrefix = $splashUrlPrefix;
		// Retrieve the split parts
		$controllerName = $this->getControllerName();
		
		// If the controller name is not specified, then let's find the root controller.
		// The root controller by convention is called "rootController".
		if (empty($controllerName)) {
			// Is there a root controller?
			try {
				$controller = MoufManager::getMoufManager()->getInstance("rootController");
				$controllerName = "rootController";
			} catch (MoufException $e) {
				// There is no root controller!
				// Let's go 404!
				Controller::FourOFour("controller.404.no.root.controller");
				exit();
			}
		} else {
			$controller = $this->getControllerInstance();
			if ($controller == null) {
				Controller::FourOFour("controller.404.no.such.controller");
				exit;
			}			
		}
		
		
		$action = $this->getAction();
		
		$this->log->trace("Routing user with URL ".$_SERVER['REDIRECT_URL']." to controller ".$controllerName." and action ".$action);
		
		if (!$controller instanceof Controller) {
			// "Invalid class";
			Controller::FourOFour("controller.404.class.doesnt.extends.controller");
			exit();
		}

		// Let's pass everything to the controller:
		$controller->callAction($action);
		
	}
}

?>