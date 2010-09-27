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

	public function getJsonArray() {
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		throw new Exception ("Not implemented yet!");
	}
	
}

?>