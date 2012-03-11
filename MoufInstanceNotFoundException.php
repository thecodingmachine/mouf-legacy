<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 
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
	
	public function __construct($msg, $code = null, $instanceName = null) {
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