<?php
require_once dirname(__FILE__)."/../views/404.php";
require_once dirname(__FILE__)."/../views/500.php";
require_once ROOT_PATH.'mouf/reflection/MoufReflectionClass.php';

abstract class Controller implements Scopable, UrlProviderInterface {

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
		self::getLogger()->error($e);

		$debug = MoufManager::getMoufManager()->getInstance("splash")->debugMode;

		if (!headers_sent()) {
			$template = self::getTemplate();
			if($e instanceof ApplicationException ) {
				$template->addContentFunction("FiveOO",$e,$debug);
			}else {
				$template->addContentFunction("UnhandledException",$e,$debug);
			}
			$template->draw();
		} else {
			UnhandledException($e,$debug);
		}

	}

	public static function FourOFour($message, $debugMode) {

		$text = "The page you request is not available. Please use <a href='".ROOT_URL."'>this link</a> to return to the home page.";
		
		if ($debugMode) {
			$text .= "<div class='info'>".$message.'</div>';
		}

		self::getLogger()->info("404 : ".$message);

		header("HTTP/1.0 404 Not Found");
		$template = self::getTemplate();
		$template->addContentFunction("FourOFour",$text)
				 ->setTitle("404 - Not Found");

		$template->draw();
	}

	/**
	 * Executes the action passed in parameter.
	 * 
	 * @param string $method The method name to be called.
	 */
	public function callAction($method) {
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

				// Ok, now, let's analyse the parameters.
				$argsArray = $this->mapParameters($refMethod);

				//call_user_func_array(array($this,$method), AdminBag::getInstance()->argsArray);
				//$result = call_user_func_array(array($this,$method), $argsArray);
				$result = $this->executeActionMethod($method, $argsArray);
				
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
	 * Analyses the method, the annotation parameters, and returns an array to be passed to the method.
	 */
	protected function mapParameters(MoufReflectionMethod $refMethod) {
		$parameters = $refMethod->getParameters();

		// Let's analyze the @param annotations.
		$paramAnnotations = $refMethod->getAnnotations('param');
		
		$values = array();
		foreach ($parameters as $parameter) {
			// First step: let's see if there is an @param annotation for that parameter.
			$found = false;
			if ($paramAnnotations != null) {
				foreach ($paramAnnotations as $annotation) {
					/* @var paramAnnotation $annotation */
					
					if ($annotation->getParameterName() == $parameter->getName()) {
						$value = $annotation->getValue();
						
						if ($value !== null) {
							$values[] = $value;
						} else {
							if ($parameter->isDefaultValueAvailable()) {
								$values[] = $parameter->getDefaultValue();
							} else {
								// No default value and no parameter... this is an error!
								// TODO: we could provide a special annotation to redirect on another action on error.
								$application_exception = new ApplicationException();
								$application_exception->setTitle("controller.incorrect.parameter.title",$refMethod->getDeclaringClass()->getName(),$refMethod->getName(),$parameter->getName());
								$application_exception->setMessage("controller.incorrect.parameter.text",$refMethod->getDeclaringClass()->getName(),$refMethod->getName(),$parameter->getName());
								throw $application_exception;
							}
						}
						$found = true;
						break;
					}
				}
			}
			
			if (!$found) {
				// There is no annotation for the parameter.
				// Let's map it to the request.
				$paramValue = get($parameter->getName());

				if ($paramValue !== false) {
					$values[] = $paramValue;
				} else {
					if ($parameter->isDefaultValueAvailable()) {
						$values[] = $parameter->getDefaultValue();
					} else {
						// No default value and no parameter... this is an error!
						// TODO: we could provide a special annotation to redirect on another action on error.
						$application_exception = new ApplicationException();
						$application_exception->setTitle("controller.incorrect.parameter.title",$refMethod->getDeclaringClass()->getName(),$refMethod->getName(),$parameter->getName());
						$application_exception->setMessage("controller.incorrect.parameter.text",$refMethod->getDeclaringClass()->getName(),$refMethod->getName(),$parameter->getName());
						throw $application_exception;
					}
				}
			}


		}

		return $values;
	}
	
	/**
	 * Returns the default template used in Splash.
	 * This can be configured in the "splash" instance.
	 *
	 * @return TemplateInterface
	 */
	public static function getTemplate() {
		$template = MoufManager::getMoufManager()->getInstance("splash")->defaultTemplate;
		return $template;
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
	 * 
	 * @return LogInterface
	 */
	public static function getLogger() {
		return MoufManager::getMoufManager()->getInstance("splash")->log;
	}
	
	/**
	 * Returns the list of URLs that can be accessed, and the function/method that should be called when the URL is called.
	 * 
	 * @return array<SplashCallback>
	 */
	public function getUrlsList() {
		// Let's analyze the controller and get all the @Action annotations:
		$urlsList = array();
		$moufManager = MoufManager::getMoufManager();
		
		$refClass = new MoufReflectionClass(get_class($this));
		
		foreach ($refClass->getMethods() as $refMethod) {
			/* @var $refMethod MoufReflectionMethod */

			// First, let's check the "Action" annotation	
			if ($refMethod->hasAnnotation('Action')) {
				$methodName = $refMethod->getName(); 
				if ($methodName == "index" || $methodName == "defaultAction") {
					$url = $moufManager->findInstanceName($this)."/";
				} else {
					$url = $moufManager->findInstanceName($this)."/".$methodName;
				}
				$urlsList[] = new SplashCallback($url, $moufManager->findInstanceName($this), $refMethod->getName(), $refMethod->getDocCommentWithoutAnnotations());
			}

			// Now, let's check the "URL" annotation (note: we support multiple URL annotations for the same method)
			if ($refMethod->hasAnnotation('URL')) {
				$urls = $refMethod->getAnnotations('URL');
				foreach ($urls as $urlAnnotation) {
					/* @var $urlAnnotation URLAnnotation */
					$url = $urlAnnotation->getUrl();
					$url = trim($url, "/");
				}
				
				$urlsList[] = new SplashCallback($url, $moufManager->findInstanceName($this), $refMethod->getName(), $refMethod->getDocCommentWithoutAnnotations());
			}
			
		}
		
		
		return $urlsList;
	}
}
?>