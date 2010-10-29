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
	
	/**
	 * Returns a PHP array that describes the group.
	 * The array does not contain all available information, only enough information to display the list of packages in the Mouf interface.
	 * 
	 * The structure of the array is:
	 * 	array("subGroups" => array('subGroupName' => subGroupArray, 'packages' => array('packageName', packageArray)
	 * 
	 * return array
	 */
	public function getJsonArray() {
		$array = array();
		if (!empty($this->subGroups)) {
			$array['subgroups'] = array();
			foreach ($this->subGroups as $name => $subGroup) {
				$array['subgroups'][$name] = $subGroup->getJsonArray();
			}
		}
		if (!empty($this->packages)) {
			$array['packages'] = array();
			foreach ($this->packages as $name => $package) {
				/* @var $package MoufPackageVersionsContainer */
				$array['packages'][$name] = $package->getJsonArray();
			}
		}
		return $array;		
	}
}

?>