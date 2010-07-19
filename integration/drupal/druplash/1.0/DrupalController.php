<?php

/**
 * This class represents a Splash Controller that has been adapted to run correctly with Drupal.
 * This is a usual controller expect it has a few utility functions added, like "addContentFile", "addContentText", "addContentFunction", etc...
 * 
 * Also, it will make sure that any output "echoed" is returned to Drupal, using a output buffer.
 * 
 * @author David
 */
abstract class DrupalController extends Controller {
	
	/**
	 * The HTML elements that will be written in the <head> tag.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */	
	public $head;
	
	/**
	 * The HTML elements that will be displayed on the center of the screen.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	protected $content = array();
	
	/**
	 * Adds some content to the main panel by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->content[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the main panel by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addContentText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->content[] = $content;
		return $this;
	}
	
	/**
	 * Adds some content to the main panel by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addContentFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		if ($scope != null) {
			$content->scope = $scope;
		} else {
			$content->scope = $this;
		}
		$this->content[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the content of the page.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addContentHtmlElement(HtmlElementInterface $element) {
		$this->content[] = $element;
		return $this;
	}
	
	/**
	 * Adds some content to the <head> tag by calling the function passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadFunction($function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->head[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by displaying the text passed in parameter.
	 * @return SplashTemplate
	 */
	public function addHeadText($text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->head[] = $content;
		return $this;
	}

	/**
	 * Adds some content to the <head> tag by displaying the text in the file passed in parameter.
	 * The scope is the object that will refer the $this.
	 * @return SplashTemplate
	 */
	public function addHeadFile($fileName, Scopable $scope = null) {
		$content = new HtmlFromFile();
		$content->fileName = $fileName;
		$content->scope = $scope;
		$this->head[] = $content;
		
		return $this;
	}
	
	/**
	 * Adds an object extending the HtmlElementInterface interface to the head of the template.
	 *
	 * @param HtmlElementInterface $element
	 * @return SplashTemplate
	 */
	public function addHeadHtmlElement(HtmlElementInterface $element) {
		$this->head[] = $element;
		return $this;
	}
	
	/**
	 * Calls the action (and does only call the action).
	 * 
	 * @param string $methodName
	 * @param array $args
	 */
	protected function executeActionMethod($methodName, $args) {
		if (!empty($this->head)) {
			ob_start();
			foreach ($this->head as $element) {
				$element->toHtml();
			}
			$head = ob_get_clean();
			drupal_set_html_head($head);
		}
		
		ob_start();
		try {
			echo parent::executeActionMethod($methodName, $args);
		} catch (Exception $e) {
			ob_end_clean();
			throw $e;
		}
		foreach ($this->content as $element) {
			$element->toHtml();
		}
		return ob_get_clean();
	}
	
	/**
	 * Sets the title of the webpage.
	 * It also sets the title in the template.
	 * 
	 * @param $title
	 */
	public function setTitle($title) {
		drupal_set_title($title);
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
				
				// If @DrupalAjax is set, we must echo the result, instead of returning it. This way, Drupal will not theme the output.
				if ($refMethod->hasAnnotation('DrupalAjax') == true) {
					echo $result;
				} else {
					return $result;
				}
				
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
	
	public function handleException (Exception $e) {
		ob_start();
		if (!headers_sent()) {			
			// FIXME: manage the debug mode again.
			if($e instanceof ApplicationException ) {
				FiveOO($e, true);
				//$template->addContentFunction("FiveOO",$e,$debug);
			}else {
				UnhandledException($e, true);
				//$template->addContentFunction("UnhandledException",$e,$debug);
			}
			//$template->draw();
			
		} else {
			UnhandledException($e,$debug);
		}
		return ob_get_clean();
	}
}