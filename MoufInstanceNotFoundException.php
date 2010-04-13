<?php
/**
 * MoufInstanceNotFoundException are thrown by the Mouf framework when the user
 * request an instance that is not defined.
 *
 */
class MoufInstanceNotFoundException extends MoufException {

	/**
	 * The name of the instance that was not found.
	 *
	 * @var string
	 */
	private $instanceName;
	
	public function __construct($msg, $code, $instanceName) {
		parent::__construct($msg, $code);
		$this->instanceName = $instanceName;
	}
	
	/**
	 * Returns the name of the instance that was not found.
	 *
	 * @return string
	 */
	public function getMissingInstanceName() {
		return $this->instanceName;
	}
}
?>