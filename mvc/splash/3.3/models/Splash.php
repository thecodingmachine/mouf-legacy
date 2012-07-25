<?php

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
	 * Splash uses the cache service to store the URL mapping (the mapping between a URL and its controller/action)
	 *
	 * @Property
	 * @Compulsory
	 * @var CacheInterface
	 */
	public $cacheService;

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
	 * FIXME: currently unused
	 * 
	 * @Property
	 * @var array<string,SplashAction>
	 */
	public $routeMap;

	/**
	 * 
	 *
	 * @var string
	 */
	private $splashUrlPrefix;

	/**
	 * Route the user to the right controller according to the URL.
	 * 
	 * @param string $splashUrlPrefix The beginning of the URL before Splash is activated. This is basically the webapp directory name.
	 * @throws Exception
	 */
	public function route($splashUrlPrefix) {

		if ($this->cacheService == null) {
			// Retrieve the split parts
			$urlsList = $this->getSplashActionsList();
			$urlNodes = $this->generateUrlNode($urlsList);
		} else {
			$urlNodes = $this->cacheService->get("splashUrlNodes");
			if ($urlNodes == null) {
				// No value in cache, let's get the URL nodes
				$urlsList = $this->getSplashActionsList();
				$urlNodes = $this->generateUrlNode($urlsList);
				$this->cacheService->set("splashUrlNodes", $urlNodes);
			}
		}
		
		// TODO: add support for %instance% for injecting the instancename of the controller
		
		$redirect_uri = $_SERVER['REDIRECT_URL'];
		$httpMethod = $_SERVER['REQUEST_METHOD'];

		$pos = strpos($redirect_uri, $splashUrlPrefix);
		if ($pos === FALSE) {
			throw new Exception('Error: the prefix of the web application "'.$splashUrlPrefix.'" was not found in the URL. The application must be misconfigured. Check the ROOT_URL parameter in your MoufUniversalParameters.php file at the root of your project.');
		}

		$tailing_url = substr($redirect_uri, $pos+strlen($splashUrlPrefix));

		$context = new SplashRequestContext();
		$splashRoute = $urlNodes->walk($tailing_url, $httpMethod);

		
		if ($splashRoute == null) {
			// Let's go for the 404
			$this->print404(SplashUtils::translate("404.page.not.found"));
			exit();
		}
		$controller = MoufManager::getMoufManager()->getInstance($splashRoute->controllerInstanceName);
		$action = $splashRoute->methodName;
		
		$context->setUrlParameters($splashRoute->filledParameters);
		

		if ($this->log != null) {
			$this->log->trace("Routing user with URL ".$_SERVER['REDIRECT_URL']." to controller ".get_class($controller)." and action ".$action);
		}

		if ($controller instanceof Controller) {
			// Let's pass everything to the controller:
			try {
				$args = array();
				foreach ($splashRoute->parameters as $paramFetcher) {
					/* @var $param SplashParameterFetcherInterface */
					try {
						$args[] = $paramFetcher->fetchValue($context);
					} catch (SplashValidationException $e) {
						
						$e->setPrependedMessage(SplashUtils::translate("validate.error.while.validating.parameter", $paramFetcher->getName()));
						throw $e;
					}
				}
					
				// Handle action__GET or action__POST method (for legacy code).
				if(method_exists($controller, $action.'__'.$_SERVER['REQUEST_METHOD'])) {
					$action = $action.'__'.$_SERVER['REQUEST_METHOD'];
				}
				
				$filters = $splashRoute->filters;
				
				// Apply filters
				for ($i=count($filters)-1; $i>=0; $i--) {
					$filters[$i]->beforeAction();
				}
			
				// Ok, now, let's store the parameters.
				//call_user_func_array(array($this,$method), AdminBag::getInstance()->argsArray);
				//$result = call_user_func_array(array($this,$method), $argsArray);
				$result = call_user_func_array(array($controller,$action), $args);
			
				foreach ($filters as $filter) {
					$filter->afterAction();
				}
			}
			catch (Exception $e) {
				return $this->handleException($e);
			}
			
			
			
		} elseif ($controller instanceof WebServiceInterface) {
			// FIXME: handle correctly webservices
			$this->handleWebservice($controller);
		} else {
			// "Invalid class";
			$this->print404("The class ".get_class($controller)." should extend the Controller class or the WebServiceInterface class.");
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
	
	/**
	 * Returns the list of all SplashActions.
	 * This call is LONG and should be cached
	 * 
	 * @return array<SplashAction>
	 */
	private function getSplashActionsList() {
		$moufManager = MoufManager::getMoufManager();
		$instanceNames = $moufManager->findInstances("UrlProviderInterface");
		
		$urls = array();
		
		foreach ($instanceNames as $instanceName) {
			$urlProvider = $moufManager->getInstance($instanceName);
			/* @var $urlProvider UrlProviderInterface */
			$tmpUrlList = $urlProvider->getUrlsList();
			$urls = array_merge($urls, $tmpUrlList);
		}
		
		
		return $urls;
	}
	
	/**
	 * Generates the URLNodes from the list of URLS.
	 * URLNodes are a very efficient way to know whether we can access our page or not.
	 * 
	 * @param array<SplashAction> $urlsList
	 * @return SplashUrlNode
	 */
	private function generateUrlNode($urlsList) {
		$urlNode = new SplashUrlNode();
		foreach ($urlsList as $splashAction) {
			$urlNode->registerCallback($splashAction);
		}
		return $urlNode;
	}
	
	private function handleException (Exception $e) {
		$logger = $this->log;
		if ($logger != null) {
			$logger->error($e);
		}
	
		$debug = $this->debugMode;
	
		
		if (!headers_sent() && !ob_get_contents()) {
			$template = $this->defaultTemplate;
			if($e instanceof ApplicationException ) {
				if ($template != null) {
					$template->addContentFunction("FiveOO",$e,$debug);
				} else {
					FiveOO($e,$debug);
				}
			}else {
				if ($template != null) {
					$template->addContentFunction("UnhandledException",$e,$debug);
				} else {
					UnhandledException($e, $debug);
				}
			}
			if ($template != null) {
				$template->draw();
			}
		} else {
			UnhandledException($e,$debug);
		}
	
	}
	
	public function print404($message) {
	
		$text = "The page you request is not available. Please use <a href='".ROOT_URL."'>this link</a> to return to the home page.";
	
		if ($this->debugMode) {
			$text .= "<div class='info'>".$message.'</div>';
		}
	
		if ($this->log != null) {
			$this->log->info("HTTP 404 : ".$message);
		}
	
	
		header("HTTP/1.0 404 Not Found");
		if ($this->defaultTemplate != null) {
			$this->defaultTemplate->addContentFunction("FourOFour",$text)
			->setTitle("404 - Not Found");
			$this->defaultTemplate->draw();
		} else {
			FourOFour($text);
		}
	
	}
	
	/**
	 * Purges the urls cache.
	 * @throws Exception
	 */
	public function purgeUrlsCache() {
		$this->cacheService->purge("splashUrlNodes");
	}
}

?>