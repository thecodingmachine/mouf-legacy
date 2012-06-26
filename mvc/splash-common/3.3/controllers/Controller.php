<?php
require_once dirname(__FILE__)."/../views/404.php";
require_once dirname(__FILE__)."/../views/500.php";
require_once ROOT_PATH.'mouf/reflection/MoufReflectionClass.php';

abstract class Controller implements Scopable, UrlProviderInterface {
	
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
	 * @return array<SplashRoute>
	 */
	public function getUrlsList() {		
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
				$parameters = SplashUtils::mapParameters($refMethod);
				$filters = FilterUtils::getFilters($refMethod, $this);
				$urlsList[] = new SplashRoute($url, $moufManager->findInstanceName($this), $refMethod->getName(), $title, $refMethod->getDocCommentWithoutAnnotations(), $refMethod->getDocComment(), $this->getSupportedHttpMethods($refMethod), $parameters, $filters);
			}

			// Now, let's check the "URL" annotation (note: we support multiple URL annotations for the same method)
			if ($refMethod->hasAnnotation('URL')) {
				$urls = $refMethod->getAnnotations('URL');
				foreach ($urls as $urlAnnotation) {
					/* @var $urlAnnotation URLAnnotation */
					$url = $urlAnnotation->getUrl();
					$url = ltrim($url, "/");
				}
				$parameters = SplashUtils::mapParameters($refMethod);
				$filters = FilterUtils::getFilters($refMethod, $this);
				$urlsList[] = new SplashRoute($url, $moufManager->findInstanceName($this), $refMethod->getName(), $title, $refMethod->getDocCommentWithoutAnnotations(), $refMethod->getDocComment(), $this->getSupportedHttpMethods($refMethod), $parameters, $filters);
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
		if ($refMethod->hasAnnotation('Put')) {
			$methods[] = "PUT";
		}
		if ($refMethod->hasAnnotation('Delete')) {
			$methods[] = "DELETE";
		}
		return $methods;
	}
}
?>