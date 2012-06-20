<?php
require_once dirname(__FILE__)."/../views/404.php";
require_once dirname(__FILE__)."/../views/500.php";
require_once ROOT_PATH.'mouf/reflection/MoufReflectionClass.php';

abstract class Controller implements Scopable, UrlProviderInterface {
	
	/**
	 * Passed Arguments
	 */
	private $args;

	/**
	 * Function called before an action is executed
	 */
	protected function beforeActionExecute($filters) {
		for ($i=count($filters)-1; $i>=0; $i--) {
			$filters[$i]->beforeAction();
		}
	}

	/**
	 * Function called after an action is executed
	 */
	protected function afterActionExecute($filters) {
		foreach ($filters as $filter) {
			$filter->afterAction();
		}
	}

	public function handleException (Exception $e) {
		$logger = self::getLogger();
		if ($logger != null) {
			self::getLogger()->error($e);
		}

		$debug = MoufManager::getMoufManager()->getInstance("splash")->debugMode;

		if (!headers_sent()) {
			$template = self::getTemplate();
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

	public static function FourOFour($message, $debugMode) {

		$text = "The page you request is not available. Please use <a href='".ROOT_URL."'>this link</a> to return to the home page.";
		
		if ($debugMode) {
			$text .= "<div class='info'>".$message.'</div>';
		}

		$logger = self::getLogger();
		if ($logger != null) {
			$logger->info("404 : ".$message);
		}
		

		header("HTTP/1.0 404 Not Found");
		$template = self::getTemplate();
		if ($template != null) {
			$template->addContentFunction("FourOFour",$text)
					 ->setTitle("404 - Not Found");
			$template->draw();
		} else {
			FourOFour($text);
		}
		
	}

	/**
	 * Executes the action passed in parameter.
	 * 
	 * @param string $method The method name to be called.
	 */
	public function callAction($method, $args) {
		// Default action is "defaultAction" or "index"
		
		if (empty($method)) {
			// Support for both defaultAction, and if not foudn "index" method.
			if (method_exists($this,"defaultAction")) {
				$method = "defaultAction";
			} else {
				$method = "index";
			}
		}

		if (method_exists($this,$method)) {
			// Ok, is this method an action?
			$refClass = new MoufReflectionClass(get_class($this));
			$refMethod = $refClass->getMethod($method);    // $refMethod is an instance of stubReflectionMethod
			//$this->getLogger()->trace("REF METHOD : ".$refMethod." // has annotation Action ? ".$refMethod->hasAnnotation('Action'));
			if ($refMethod->hasAnnotation('Action') == false) {
				$debug = MoufManager::getMoufManager()->getInstance("splash")->debugMode;
				// This is not an action. Let's go in error.
				self::FourOFour(iMsg("controller.404.no.action", get_class($this), $method), $debug);
				exit;
			}

			try {
				$filters = FilterUtils::getFilters($refMethod, $this);
				$this->beforeActionExecute($filters);

				// Ok, now, let's store the parameters.
				$this->args = $args;
				//call_user_func_array(array($this,$method), AdminBag::getInstance()->argsArray);
				//$result = call_user_func_array(array($this,$method), $argsArray);
				$result = $this->executeActionMethod($method, $this->args);
				
				$this->afterActionExecute($filters);
				return $result;
			}
			catch (Exception $e) {
				return $this->handleException($e);
			}
		}else {
			// "Method Not Found";
			$debug = MoufManager::getMoufManager()->getInstance("splash")->debugMode;
			self::FourOFour("404.wrong.method", $debug);
			exit;
		}
		
	}

	/**
	 * Calls the action (and does only call the action).
	 * 
	 * @param string $methodName
	 * @param array $args
	 */
	protected function executeActionMethod($methodName, $args) {
		return call_user_func_array(array($this,$methodName), $args);
	}
	

	
	/**
	 * Returns the default template used in Splash.
	 * This can be configured in the "splash" instance.
	 * Returns null if the "splash" instance does not exist.
	 *
	 * @return TemplateInterface
	 */
	public static function getTemplate() {
		if (MoufManager::getMoufManager()->instanceExists("splash")) {
			$template = MoufManager::getMoufManager()->getInstance("splash")->defaultTemplate;
			return $template;
		} else {
			return null;
		}
	}

	/**
	 * Inludes the file (useful to load a view inside the Controllers scope).
	 *
	 * @param unknown_type $file
	 */
	public function loadFile($file) {
		include $file;
	}
	
	/**
	 * Returns an instance of the logger used by default in Splash.
	 * This logger can be configured in the "splash" instance.
	 * Note: in Drusplash, there is no such "splash" instance. Therefore, null will be returned.
	 * 
	 * @return LogInterface
	 */
	public static function getLogger() {
		if (MoufManager::getMoufManager()->instanceExists("splash")) {
			return MoufManager::getMoufManager()->getInstance("splash")->log;
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the list of URLs that can be accessed, and the function/method that should be called when the URL is called.
	 * 
	 * @return array<SplashCallback>
	 */
	public function getUrlsList() {
		// Let's perform a late loading on the SplashCallback class (because the admin version of Mouf might use a different version of the class than the application
		// itself, we cannot include this file directly, since it is used inside the admin of mouf).
		require_once dirname(__FILE__)."/../services/SplashCallback.php";
		
		// Let's analyze the controller and get all the @Action annotations:
		$urlsList = array();
		$moufManager = MoufManager::getMoufManager();
		
		$refClass = new MoufReflectionClass(get_class($this));
		
		foreach ($refClass->getMethods() as $refMethod) {
			/* @var $refMethod MoufReflectionMethod */
			$title = null;
			// Now, let's check the "Title" annotation (note: we do not support multiple title annotations for the same method)
			if ($refMethod->hasAnnotation('Title')) {
				$titles = $refMethod->getAnnotations('Title');
				if (count($titles)>1) {
					throw new ApplicationException("Only one @Title annotation allowed per method.");
				}
				/* @var $titleAnnotation TitleAnnotation */
				$titleAnnotation = $titles[0];
				$title = $titleAnnotation->getTitle();
			}
			
			// First, let's check the "Action" annotation	
			if ($refMethod->hasAnnotation('Action')) {
				$methodName = $refMethod->getName(); 
				if ($methodName == "index" || $methodName == "defaultAction") {
					$url = $moufManager->findInstanceName($this)."/";
				} else {
					$url = $moufManager->findInstanceName($this)."/".$methodName;
				}
				$urlsList[] = new SplashCallback($url, $moufManager->findInstanceName($this), $refMethod->getName(), $title, $refMethod->getDocCommentWithoutAnnotations(), $refMethod->getDocComment(), $this->getSupportedHttpMethods($refMethod));
			}

			// Now, let's check the "URL" annotation (note: we support multiple URL annotations for the same method)
			if ($refMethod->hasAnnotation('URL')) {
				$urls = $refMethod->getAnnotations('URL');
				foreach ($urls as $urlAnnotation) {
					/* @var $urlAnnotation URLAnnotation */
					$url = $urlAnnotation->getUrl();
					$url = trim($url, "/");
				}
				
				$urlsList[] = new SplashCallback($url, $moufManager->findInstanceName($this), $refMethod->getName(), $title, $refMethod->getDocCommentWithoutAnnotations(), $refMethod->getDocComment(), $this->getSupportedHttpMethods($refMethod));
			}
			
		}
		
		
		return $urlsList;
	}
	
	/**
	 * Returns the supported HTTP methods on this function, based on the annotations (@Get, @Post, etc...)
	 * @param MoufReflectionMethod $refMethod
	 */
	private function getSupportedHttpMethods(MoufReflectionMethod $refMethod) {
		$methods = array();
		if ($refMethod->hasAnnotation('Get')) {
			$methods[] = "GET";
		}
		if ($refMethod->hasAnnotation('Post')) {
			$methods[] = "POST";
		}
		return $methods;
	}
}
?>