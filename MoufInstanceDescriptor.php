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
 * This object represent an instance declared in the Mouf framework.
 * 
 * @author David Negrier
 */
class MoufInstanceDescriptor {
	
	/**
	 * The MoufManager instance owning the component.
	 * @var MoufManager
	 */
	private $moufManager;
	
	/**
	 * The name of the instance.
	 * 
	 * @var string
	 */
	private $name;
	
	/**
	 * A list of properties (not the list of all properties).
	 * Used for caching.
	 * 
	 * @var array<MoufInstancePropertyDescriptor>
	 */
	private $properties = array();
	
	/**
	 * The constructor should exclusively be used by MoufManager.
	 * Use MoufManager::getInstanceDescriptor and MoufManager::createInstance to get instances of this class.
	 * 
	 * @param MoufManager $moufManager
	 * @param unknown_type $name
	 */
	public function __construct(MoufManager $moufManager, $name) {
		$this->moufManager = $moufManager;
		$this->name = $name;
	}
	
	/**
	 * Sets the name of this instance (or rename the instance).
	 * If $name is empty, the instance will be considered anonymous.
	 * 
	 * Note: If the instance was anonymous and it is given a name, it will be automatically declared "non-weak" (but you can set the weakness of the instance
	 * back using "setWeakness").
	 * If the instance becomes anonymous, it becomes "weak".
	 * 
	 * @param string $name
	 * @return MoufInstanceDescriptor The instance is returned, for chaining purpose.
	 */
	public function setName($name) {
		if (empty($name)) {
			$name = $this->moufManager->getFreeAnonymousName();
		}
		$unsetWeakness = false;
		if ($this->moufManager->isInstanceAnonymous($this->name) && !empty($name)) {
			$unsetWeakness = true;
		}
		$this->moufManager->renameComponent($this->name, $name);
		if ($unsetWeakness) {
			$this->moufManager->setInstanceWeakness($name, false);
			$this->moufManager->setInstanceAnonymousness($name, false);
		}
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Returns the name of the instance, or NULL if the instance is anonymous.
	 * @return string
	 */
	public function getName() {
		if ($this->moufManager->isInstanceAnonymous($this->name)) {
			return null;
		} else {
			return $this->name;
		}
	}
	
	/**
	 * Returns the classname of the instance.
	 * @return string
	 */
	public function getClassName() {
		return $this->moufManager->getInstanceType($this->name);
	}
	
	/**
	 * Returns the class descriptor for this class
	 * @return MoufXmlReflectionClass
	 */
	public function getClassDescriptor() {
		return $this->moufManager->getClassDescriptor($this->getClassName());
	}
	
	/**
	 * Returns an object describing a property of a field.
	 * 
	 * @param string $name
	 * @return MoufInstancePropertyDescriptor
	 */
	public function getProperty($name) {
		if (!isset($this->properties[$name])) {
			$this->properties[$name] = new MoufInstancePropertyDescriptor($this->moufManager, $this, $name);
		}
		return $this->properties[$name]; 
	}
	
}