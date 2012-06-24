<?php

class SplashUtils {
	
	/**
	 * 
	 * @return SplashUrlManager
	 */
	public static function getSplashUrlManager() {
		// Performs some late loading to avoid problems with the Mouf admin
		require_once 'SplashUrlManager.php';
		
		return new SplashUrlManager();
	}
	
	/**
	 * Analyses the method, the @param annotation parameters, and returns an array of SplashRequestParameterFetcher.
	 * 
	 * @return array<SplashParameterFetcherInterface>
	 */
	public static function mapParameters(MoufReflectionMethod $refMethod) {
		$parameters = $refMethod->getParameters();

	
		// Let's try to find parameters in the @URL annotation
		// Let's build a set of those parameters.
		$urlAnnotations = $refMethod->getAnnotations('URL');
		$urlParamsList = array();
		if ($urlAnnotations != null) {
			foreach ($urlAnnotations as $urlAnnotation) {
				/* @var $urlAnnotation URLAnnotation */
				$url = $urlAnnotation->getUrl();
				$urlParts = explode("/", $url);
				foreach ($urlParts as $part) {
					if (strpos($part, "{") === 0 && strpos($part, "}") === strlen($part)-1) {
						// Parameterized URL element
						$varName = substr($part, 1, strlen($part)-2);
						$urlParamsList[$varName] = $varName;
					}
				}
			}
		}
		
		// Let's analyze the @param annotations.
		$paramAnnotations = $refMethod->getAnnotations('param');
			
		$values = array();
		foreach ($parameters as $parameter) {
			// First step: let's see if there is an @param annotation for that parameter.
			$found = false;
			
			// Let's first see if our parameter is part of the URL
			if (isset($urlParamsList[$parameter->getName()])) {
				unset($urlParamsList[$parameter->getName()]);
				
				if ($parameter->isDefaultValueAvailable()) {
					$values[] = new SplashUrlParameterFetcher($parameter->getName(), false, $parameter->getDefaultValue());
				} else {
					$values[] = new SplashUrlParameterFetcher($parameter->getName(), true);
				}
				break;
			}
			
			if ($paramAnnotations != null) {
				foreach ($paramAnnotations as $annotation) {
					/* @var paramAnnotation $annotation */
						
					if (substr($annotation->getParameterName(), 1) == $parameter->getName()) {
						//$paramAnnotationAnalyzer = new ParamAnnotationAnalyzer($annotation);
						//$value = $paramAnnotationAnalyzer->getValue();

						if ($parameter->isDefaultValueAvailable()) {
							$values[] = new SplashRequestParameterFetcher($parameter->getName(), false, $parameter->getDefaultValue());
						} else {
							$values[] = new SplashRequestParameterFetcher($parameter->getName(), true);
						}

						$found = true;
						break;
					}
				}
			}
				
			if (!$found) {
				// There is no annotation for the parameter.
				// Let's map it to the request.
				
				if ($parameter->isDefaultValueAvailable()) {
					$values[] = new SplashRequestParameterFetcher($parameter->getName(), false, $parameter->getDefaultValue());
				} else {
					$values[] = new SplashRequestParameterFetcher($parameter->getName(), true);
				}
			}
		}
	
		if (!empty($urlParamsList)) {
			throw new SplashException("An error occured while handling a @URL annotation: the @URL annotation is parameterized with those variable(s): '".implode('/', $urlParamsList)."'. However, there is no such parameters in the function call.");
		}
		
		return $values;
	}
}

?>