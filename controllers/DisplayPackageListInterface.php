<?php
/**
 * An interface implemented by controllers that can display a package list (using the displayGroup function).
 */
interface DisplayPackageListInterface {
	
	/**
	 * Display the rows of buttons below the package list.
	 * 
	 * @param MoufPackage $package The package to display
	 * @param string $enabledVersion The version of that package that is currently enabled, if any.
	 */
	function displayPackageActions(MoufPackage $package, $enabledVersion);
}