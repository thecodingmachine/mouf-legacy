<?php

/**
 * This class describes a set of packages, that have the same group, the same name, but different version numbers.
 * 
 * @author David
 */
class MoufPackageVersionsContainer {
		
	/**
	 * The list of packages.
	 * The key is the version number.
	 * 
	 * @var array<string, MoufPackage>
	 */
	public $packages = array();
	 
	/**
	 * Returns the package version whose version is "$version".
	 * Returns null of the version is not found.
	 * 
	 * @param $name
	 * @return MoufPackage
	 */
	public function getPackage($version) {
		if (!isset($this->packages[$version])) {
			return null;
		}
		return $this->packages[$version];
	}
	
	public function setPackage(MoufPackage $package) {
		$this->packages[$package->getDescriptor()->getVersion()] = $package;
	}

	/**
	 * Returns a PHP array that describes the packages versions.
	 * The array does not contain all available information, only enough information to display the list of packages in the Mouf interface.
	 * 
	 * The structure of the array is:
	 * 	array(version => package)
	 * 
	 * return array<string, MoufPackage>
	 */
	public function getJsonArray() {
		$array = array();
		if (!empty($this->packages)) {
			foreach ($this->packages as $version => $package) {
				/* @var $package MoufPackage */
				$array[$version] = $package->getJsonArray();
			}
		}

		return $array;		
	}
	
}

?>