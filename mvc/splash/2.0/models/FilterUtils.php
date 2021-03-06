<?php

/**
 * Utility class for filters.
 */
class FilterUtils {

	private static $filtersList = array();

	/**
	 * Registers a new filter.
	 */
	public static function registerFilter($filterName) {
		self::$filtersList[] = $filterName;
	}

	/**
	 * Returns a list of filters instances, order by priority (higher priority first).
	 * @arg $refMethod the reference method extended object.
	 * @arg $controller the controller the annotation was in.
	 * @return array Array of filter instances sorted by priority.
	 */
	public static function getFilters(MoufReflectionMethod $refMethod, Controller $controller) {

		$filterArray = array();

		$refClass = $refMethod->getDeclaringClass();

		$parentsArray = array();
		$parentClass = $refClass;
		while ($parentClass != null) {
			$parentsArray[] = $parentClass;
			$parentClass = $parentClass->getParentClass();
		}

		// Start with the most parent class and goes to the target class:
		for ($i=count($parentsArray)-1; $i>=0; $i--) {
			foreach (self::$filtersList as $filterName) {
				if ($parentsArray[$i]->hasAnnotation($filterName)) {
					//$filterArray[$filter] = $parentsArray[$i]->getAnnotation($filter);
					//$filterArray[$filter]->setMetaData($controller, $refMethod);
					$filters = $parentsArray[$i]->getAnnotations($filterName);
					
					foreach ($filters as $filter) {
						// The filter should be a class instance extending filter.
						// If it is a string, it means the class was not properly loaded.
						if (is_string($filter)) {
							throw new Exception("Error while handling filter annotation: @$filterName. It seems that neither the class $filterName nor ".$filterName."Annotation does exist.");
						}
						
						if (!$filter instanceof AbstractFilter) {
							throw new Exception("Error while handling filter annotation: @$filterName. The ".get_class($filter)." class must extend the AbstractFilter class.");
						}
						
						$filter->setMetaData($controller, $refMethod);
						$filterArray[] = $filter;
					}
					
				}
			}
		}

		// Continue with the method (and eventually override class parameters)
		foreach (self::$filtersList as $filterName) {
			if ($refMethod->hasAnnotation($filterName)) {
				//$filterArray[$filter] = $refMethod->getAnnotation($filter);
				//$filterArray[$filter]->setMetaData($controller, $refMethod);
				$filters = $refMethod->getAnnotations($filterName);
									
				foreach ($filters as $filter) {
					// The filter should be a class instance extending filter.
					// If it is a string, it means the class was not properly loaded.
					if (is_string($filter)) {
						throw new Exception("Error while handling filter annotation: @$filterName. It seems that neither the class $filterName nor ".$filterName."Annotation does exist.");
					}
					
					if (!$filter instanceof AbstractFilter) {
						throw new Exception("Error while handling filter annotation: @$filterName. The ".get_class($filter)." class must extend the AbstractFilter class.");
					}
					
					$filter->setMetaData($controller, $refMethod);
					$filterArray[] = $filter;
				}
			}
		}

		// Sort array by filter priority.
		usort($filterArray, array("AbstractFilter","compare"));

		return $filterArray;
	}
}
?>