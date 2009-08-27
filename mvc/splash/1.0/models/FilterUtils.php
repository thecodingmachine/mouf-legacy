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
	public static function getFilters(stubReflectionMethod $refMethod, Controller $controller) {

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
			foreach (self::$filtersList as $filter) {
				if ($parentsArray[$i]->hasAnnotation($filter)) {
					$filterArray[$filter] = $parentsArray[$i]->getAnnotation($filter);
					$filterArray[$filter]->setMetaData($controller, $refMethod);
				}
			}
		}

		// Continue with the method (and eventually override class parameters)
		foreach (self::$filtersList as $filter) {
			if ($refMethod->hasAnnotation($filter)) {
				$filterArray[$filter] = $refMethod->getAnnotation($filter);
				$filterArray[$filter]->setMetaData($controller, $refMethod);
			}
		}

		// Sort array by filter priority.
		usort($filterArray, array("AbstractFilter","compare"));

		return $filterArray;
	}
}
?>
