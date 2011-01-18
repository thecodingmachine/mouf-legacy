<?php
// Loading common configuration for the application
require_once dirname(__FILE__)."/../load.php";

/**
 * The Splash component is the root of the Splash framework.<br/>
 * It is in charge of binding an Url to a Controller.<br/>
 * There is one and only one instance of Splash per web application.<br/>
 * The name of the instance MUST be "splash".<br/>
 * <br/>
 * The Splash component has several ways to bind an URL to a Controller.<br/>
 * It can do so:<br/>
 * <ul><li>using the instance name of a controller that has been instanciated with Mouf.<br/>
 * For instance, if a controller has an instance name that is "myController", then
 * the http://[myserver]/[mywebapp]/myController URL will lead to the default action
 * of that controller.<br/>
 * The http://[myserver]/[mywebapp]/myController/myAction URL will lead to the myAction action
 * of that controller.</br>
 * </li></ul>
 * Others methods are remaining to be done.
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
	 * If Splash debug mode is enabled, stack traces on error messages will be displayed.
	 *
	 * @Property
	 * @var bool
	 */
	public $debugMode;

	/**
	 * Set to "true" if the server supports HTTPS.
	 * This can be used by various plugins (especially the RequiresHttps annotation).
	 *
	 * @Property
	 * @var boolean
	 */
	public $supportsHttps;

	/**
	 * Defines the route map for input URLs
	 *
	 * @Property
	 * @var array<string,SplashAction>
	 */
	public $authorizeInstanceAccess=true;

	/**
	 * Defines the route map for input URLs
	 *
	 * @Property
	 * @var array<string,SplashAction>
	 */
	public $routeMap;

	/**
	 * The controller in the Url (this should be the first "directory" of the URL after the webapp.
	 *
	 * @var Controller
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
			throw new Exception('Error: the prefix of the web application "'.$this->splashUrlPrefix.'" was not found in the URL. The application must be misconfigured. Check the ROOT_URL parameter in your MoufUniversalParameters.php file at the root of your project.');
		}

		$tailing_url = substr($redirect_uri, $pos+strlen($this->splashUrlPrefix));

		// Step 1: look at the route map
		if($this->routeMap) {
			foreach ($this->routeMap as $url=>$splashAction) {
				if(strtolower($url) == substr(strtolower($tailing_url),0,strlen($url))) {
					$this->controller = $splashAction->controller;
					$this->action = $splashAction->actionName;
					//args
					$args = substr($tailing_url, strlen($url));
					$array = explode("/", $args);
					$this->args = $array;
				}
			}
		}

		// Step 2: If no controller is found with the mapping, search in the available controllers
		// Warning : if Instance Access forbidden -> Map use only!
		if($this->authorizeInstanceAccess && !$this->controller){
			$array = explode("/", $tailing_url);

			try {
				if(isset($array[0]) && !empty($array[0])){
					$this->controller = MoufManager::getMoufManager()->getInstance($array[0]);
				}
			}catch (MoufInstanceNotFoundException $e) {
				// If the error is because there is no such instance:
				if ($e->getMissingInstanceName() == $array[0]) {
					Controller::FourOFour($e->getMessage(), $this->debugMode);
					exit;
				}
				// If the error is because there is a missing instance into the grap of objects retrieved:
				throw $e;
			}
			if (isset($array[1])) {
				$this->action = $array[1];
			}
			$this->args = array();

			array_shift($array);
			array_shift($array);

			$this->args = $array;
		}

		$this->analyzeDone = true;
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
		$this->analyze();

		// If the controller name is not specified, then let's find the root controller.
		// The root controller by convention is called "rootController".

		if (!($this->controller)) {
			// Is there a root controller?
			try {
				$controller = MoufManager::getMoufManager()->getInstance("rootController");
			} catch (MoufException $e) {
				// There is no root controller!
				// Let's go 404!
				Controller::FourOFour("No root controller found! There should be one instance in your code that is named 'rootController' in Mouf.", $this->debugMode);
				exit();
			}
		} else {
			$controller = $this->controller;
		}

		$action = $this->getAction();
		
		$this->log->trace("Routing user with URL ".$_SERVER['REDIRECT_URL']." to controller ".get_class($controller)." and action ".$action);

		if ($controller instanceof Controller) {
			// Let's pass everything to the controller:
			$controller->callAction($action);			
		} elseif ($controller instanceof WebServiceInterface) {
			$this->handleWebservice($controller);
		} else {
			// "Invalid class";
			Controller::FourOFour("The class ".get_class($controller)." should extend the Controller class or the WebServiceInterface class.", $this->debugMode);
			exit();
		}


	}
	
	/**
	 * Handles the call to the webservice
	 * 
	 * @param WebServiceInterface $webserviceInstance
	 */
	private function handleWebservice(WebServiceInterface $webserviceInstance) {
		$url = $webserviceInstance->getWebserviceUri();
		
		$server = new SoapServer(null, array('uri' => $url));
   		$server->setObject($webserviceInstance); 
   		$server->handle();
	}
}

?>