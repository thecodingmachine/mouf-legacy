<?php

/**
 * An exception thrown when enabling a new package.
 * This happens if the package to be enabled is not compatible with a previously installed package.
 * 
 * @author David
 */
class MoufIncompatiblePackageException extends MoufException {
	
	public $group;
	public $name;
	public $inPlaceVersion;
	public $requestedVersion;
	public $isInPlaceVersionInstalled;
	
	/**
	 * 
	 * @param string $group
	 * @param string $name
	 * @param string $inPlaceVersion
	 * @param string $requestedVersion
	 * @param boolean $isInPlaceVersionInstalled True if the "inPlaceVersion" is currently installed, false if it is a previous dependency that is not yet installed.
	 */
	public function __construct($group, $name, $inPlaceVersion, $requestedVersion, $isInPlaceVersionInstalled) {
		parent::__construct("An exception occured while installing incompatible packages.", 0);
		$this->group = $group;
		$this->name = $name;
		$this->inPlaceVersion = $inPlaceVersion;
		$this->requestedVersion = $requestedVersion;
		$this->isInPlaceVersionInstalled = $isInPlaceVersionInstalled;
	}	
}

?>