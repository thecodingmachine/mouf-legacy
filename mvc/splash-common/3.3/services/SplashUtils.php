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
	 * Analyses the method, the annotation parameters, and returns an array to be passed to the method.
	 */
	public static function mapParameters(MoufReflectionMethod $refMethod, $args) {
		$parameters = $refMethod->getParameters();
		
		//If the action doesn't take any excplicit arguments, let's return the existing args
		if (!$parameters){
			return $args;
		}
	
		//echo "Parameters in SplashUtils.php: ";
		//var_dump($parameters);
	
		// Let's analyze the @param annotations.
		$paramAnnotations = $refMethod->getAnnotations('param');
	
		$values = array();
		foreach ($parameters as $parameter) {
			// First step: let's see if there is an @param annotation for that parameter.
			$found = false;
			if ($paramAnnotations != null) {
				foreach ($paramAnnotations as $annotation) {
					/* @var paramAnnotation $annotation */
						
					if (substr($annotation->getParameterName(), 1) == $parameter->getName()) {
						$paramAnnotationAnalyzer = new ParamAnnotationAnalyzer($annotation);
						$value = $paramAnnotationAnalyzer->getValue();
	
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
}

?>