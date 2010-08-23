<?php

/**
 * This class describes a "group": a set of subgroups, and packages version containers.
 * Note: a package version container is a set of packages that have the same name, but different versions.
 * 
 * @author David
 */
class MoufGroupDescriptor {
	
	/**
	 * The list of subgroups that are part of this group.
	 * 
	 * @var array<string, MoufGroupDescriptor>
	 */
	public $subGroups = array();
	
	
	/**
	 * The list of packages versions (a package version container is a group of packages with the same name and different version numbers).
	 * 
	 * @var array<string, MoufPackageVersionsContainer>
	 */
	public $packages = array();
	
	/**
	 * Returns the subgroup whose name is "$name".
	 * The group is created if it does not exist.
	 * 
	 * @param $name
	 * @return MoufGroupDescriptor
	 */
	public function getGroup($name) {
		if (!isset($this->subGroups[$name])) {
			$newGroup = new MoufGroupDescriptor();
			$this->subGroups[$name] = $newGroup;
		}
		return $this->subGroups[$name];
	}
	
	/**
	 * Returns the package version container whose name is "$name".
	 * The container is created if it does not exist.
	 * 
	 * @param $name
	 * @return MoufPackageVersionsContainer
	 */
	public function getPackageContainer($name) {
		if (!isset($this->packages[$name])) {
			$newPackageContainer = new MoufPackageVersionsContainer();
			$this->packages[$name] = $newPackageContainer;
		}
		return $this->packages[$name];
	}
}

?>