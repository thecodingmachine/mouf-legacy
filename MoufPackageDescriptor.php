<?php

/**
 * A package descriptor is an object describing the location of a package (from its group, name and version number).
 *
 */
class MoufPackageDescriptor {
	/**
	 * The version number of the package (comes from the path: group/name/version)
	 *
	 * @var string
	 */
	private $version;
	
	/**
	 * The name of the package (comes from the path: group/name/version)
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * The group of the package (comes from the path: group/name/version)
	 *
	 * @var string
	 */
	private $group;
	
	public function __construct($group, $name, $version) {
		$this->version = $version;
		$this->name = $name;
		$this->group = $group;
	}
	
	/**
	 * Returns a package descriptor object from the filename. 
	 * 
	 * @param string $fileName The path to the package.xml file, relative to the root of the plugins directory.
	 * @return MoufPackageDescriptor
	 */
	public static function getPackageDescriptorFromPackageFile($fileName) {
		$packageDir = dirname($fileName);
		
		
		// From the package dir, let's find the group, the name, and the version!
		$version = basename($packageDir);
		$tmpDir = dirname($packageDir);
		$name = basename($tmpDir);
		$tmpGroup = dirname($tmpDir);
		if (strpos($tmpGroup, "./") === 0) {
			$group = substr($tmpGroup, 2);
		} else {
			$group = $tmpGroup;
		}
		
		return new MoufPackageDescriptor($group, $name, $version);
	}
	
	/**
	 * Returns the version number of the package
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}
	
	/**
	 * Returns the name of the package
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Returns the group of the package
	 *
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}
	
	/**
	 * Returns the package directory (without a trailing slash), relative to the plugins directory.
	 *
	 * @return string
	 */
	public function getPackageDirectory() {
		return $this->group."/".$this->name."/".$this->version;
	}
	
	/**
	 * Returns the path to the package.xml file from the root "plugins" directory.
	 *
	 * @return string
	 */
	public function getPackageXmlPath() {
		return $this->group."/".$this->name."/".$this->version."/package.xml";
	}
}
?>