<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 

require_once 'MoufException.php';
require_once 'MoufConfigManager.php';
require_once 'MoufInstanceNotFoundException.php';

/**
 * The class managing object instanciation in the Mouf framework.
 * Users should use the "Mouf" class instead.
 *
 */
class MoufManager {
	const SCOPE_APP = 'app';
	const SCOPE_ADMIN = 'admin';
	
	const DECLARE_ON_EXIST_EXCEPTION = 'exception';
	const DECLARE_ON_EXIST_KEEP_INCOMING_LINKS = 'keepincominglinks';
	const DECLARE_ON_EXIST_KEEP_ALL = 'keepall';
	
	/**
	 * The default instance of the MoufManager.
	 *
	 * @var MoufManager
	 */
	private static $defaultInstance;

	/**
	 * The hidden instance of the MoufManager.
	 * The hidden instance is used when there must be more than one instance of Mouf loaded.
	 * This happens for instance in the Mouf adminsitration screens:
	 * The Mouf admin components are stored in the default instance, while the configuration of the application
	 * being designed is stored in the hiddenInstance.
	 *
	 * @var MoufManager
	 */
	private static $hiddenInstance;

	/**
	 * Returns the default instance of the MoufManager.
	 *
	 * @return MoufManager
	 */
	public static function getMoufManager() {
		return self::$defaultInstance;
	}

	/**
	 * Returns the hidden instance of the MoufManager.
	 * The hidden instance is used when there must be more than one instance of Mouf loaded.
	 * This happens for instance in the Mouf adminsitration screens:
	 * The Mouf admin components are stored in the default instance, while the configuration of the application
	 * being designed is stored in the hiddenInstance.
	 *
	 * @return MoufManager
	 */
	public static function getMoufManagerHiddenInstance() {
		return self::$hiddenInstance;
	}
	
	/**
	 * Returns true if there is a hidden instance (which probably means we are in the Mouf admin console).
	 *
	 * @return boolean
	 */
	public static function hasHiddenInstance() {
		return (self::$hiddenInstance != null);
	}
	
	/**
	 * Instantiates the default instance of the MoufManager.
	 * Does nothing if the default instance is already instanciated.
	 */
	public static function initMoufManager() {
		if (self::$defaultInstance == null) {
			self::$defaultInstance = new MoufManager();
			self::$defaultInstance->configManager = new MoufConfigManager("../config.php");
			self::$defaultInstance->componentsFileName = "../MoufComponents.php";
			self::$defaultInstance->requireFileName = "../MoufRequire.php";
			self::$defaultInstance->adminUiFileName = "../MoufUI.php";
			self::$defaultInstance->mainClassName = "Mouf";
			self::$defaultInstance->pathToMouf = "mouf/";
			self::$defaultInstance->scope = MoufManager::SCOPE_APP;
		}
	}
	
	/**
	 * This function takes the whole configuration stored in the default instance of the Mouf framework
	 * and switches it in the hidden instance.
	 * The default instance is cleaned afterwards.
	 *
	 */
	public static function switchToHidden() {
		self::$hiddenInstance = self::$defaultInstance;
		self::$defaultInstance = new MoufManager();
		self::$defaultInstance->configManager = new MoufConfigManager("config.php");
		self::$defaultInstance->componentsFileName = "MoufAdminComponents.php";
		self::$defaultInstance->requireFileName = "MoufAdminRequire.php";
		self::$defaultInstance->adminUiFileName = "MoufAdminUI.php";
		self::$defaultInstance->mainClassName = "MoufAdmin";
		self::$defaultInstance->scope = MoufManager::SCOPE_ADMIN;
		self::$defaultInstance->pathToMouf = "";
	}
	
	/**
	 * The config manager (that writes the config.php file).
	 *
	 * @var MoufConfigManager
	 */
	private $configManager;
	
	/**
	 * The array of component instances managed by mouf.
	 * The objects in this array have been already instanciated.
	 *
	 * @var array<string, object>
	 */
	private $objectInstances = array();
	
	/**
	 * The array of component instances that have been declared.
	 * This array contains the definition that will be used to create the instances.
	 *
	 * $declaredInstance["instanceName"] = $instanceDefinitionArray;
	 * 
	 * $instanceDefinitionArray["class"] = "string"
	 * $instanceDefinitionArray["fieldProperties"] = array("propertyName" => $property);
	 * $instanceDefinitionArray["setterProperties"] = array("setterName" => $property);
	 * $instanceDefinitionArray["fieldBinds"] = array("propertyName" => "instanceName");
	 * $instanceDefinitionArray["setterBinds"] = array("setterName" => "instanceName");
	 * $instanceDefinitionArray["comment"] = "string"
	 * $instanceDefinitionArray["weak"] = true|false (if true, object can be garbage collected if not referenced)
	 * $instanceDefinitionArray["anonymous"] = true|false (if true, object name should not be displayed. Object becomes "weak")
	 * $instanceDefinitionArray["external"] = true|false
	 * 
	 * $property["type"] = "string|config|request|session";
	 * $property["value"] = $value;
	 * $property['metadata'] = array($key=>$value)
	 * 
	 * @var array<string, array>
	 */
	private $declaredInstances = array();
	
		
	/**
	 * A list of files to be required (relative to the directory of Mouf.php)
	 *
	 * @var array
	 */
	private $registeredComponents = array();
	
	/**
	 * A list of components name that are external.
	 * External components are not saved when the rewriteMouf method is called.
	 * They are useful for declaring components instances that should not be modified. 
	 *
	 * @var array<string>
	 */
	private $externalComponents = array();
	
	/**
	 * The list of packages that are enabled.
	 * The list contains the path to the package.xml file from the plugins directory.
	 * The list is ordered per dependencies.
	 *
	 * @var array<string>
	 */
	private $packagesList = array();
	
	/**
	 * The list of packages that are enabled in admin scope.
	 * The list contains the path to the package.xml file from the plugins directory.
	 * The list is ordered per dependencies.
	 * This list is filled in the MoufManager instance of the APP scope, and is always
	 * empty in the MoufManager instance of the ADMIN scope
	 *
	 * @var array<string>
	 */
	private $packagesListInAdminScope = array();
	
	
	/**
	 * A list of variables that are stored in Mouf. Variables can contain anything, and are used by some modules for different
	 * purposes. For instance, the list of repositories is stored as a variables, etc...
	 * 
	 * @var array<string, mixed>
	 */
	private $variables = array();
	
	/**
	 * The name of the file that contains the components declarations
	 *
	 * @var string
	 */
	private $componentsFileName;
	
	/**
	 * The name of the file that contains the "requires" on the components
	 *
	 * @var string
	 */
	private $requireFileName;

	/**
	 * The scope for the MoufManager.
	 * Can be one of MoufManager::SCOPE_APP (the main application) or MoufManager::SCOPE_ADMIN (the Mouf instance for the admin)
	 * 
	 * @var string
	 */
	private $scope;
	
	/**
	 * The name of the file that contains the "requires" on the components for the admin part of Mouf
	 *
	 * @var string
	 */
	private $adminUiFileName;
	
	/**
	 * The name of the main class that will be generated (by default: Mouf)
	 *
	 * @var string
	 */
	private $mainClassName;
	
	/**
	 * The path to theMouf directory from the mouf file.
	 * For instance: "mouf/" is the Mouf.php file is in the root directory of the webapp.
	 * 
	 * @var string
	 */
	private $pathToMouf;
	
	/**
	 * A list of classes autoloadable that are stored in Mouf.
	 * 
	 * @var array<className, fileName>
	 */
	private $autoloadableClasses;
	
	/**
	 * Returns the config manager (the service in charge of writing the config.php file).
	 *
	 * @return MoufConfigManager
	 */
	public function getConfigManager() {
		return $this->configManager;
	}
	
	/**
	 * Returns the instance of the specified object.
	 *
	 * @param string $instanceName
	 */
	public function getInstance($instanceName) {
		if (!isset($this->objectInstances[$instanceName]) || $this->objectInstances[$instanceName] == null) {
			$this->instantiateComponent($instanceName);
		}
		return $this->objectInstances[$instanceName];
	}
	
	/**
	 * Returns true if the instance name passed in parameter is defined in Mouf.
	 * 
	 * @param string $instanceName
	 */
	public function instanceExists($instanceName) {
		return isset($this->declaredInstances[$instanceName]);
	}
	
	/**
	 * Returns the list of all instances of objects in Mouf.
	 * Objects are not instanciated. Instead, a list containing the name of the instance in the key
	 * and the name of the class in the value is returned.
	 *
	 * @return array<string, string>
	 */
	public function getInstancesList() {
		$arr = array();
		foreach ($this->declaredInstances as $instanceName=>$classDesc) {
			//if (!isset($classDesc["class"])) {var_dump($instanceName);var_dump($classDesc);}
			$arr[$instanceName] = $classDesc['class'];
		}
		return $arr;
	}
	
	/**
	 * Sets at one all the instances of all the components.
	 * This is used internally to load the state of Mouf very quickly.
	 * Do not use directly.
	 *
	 * @param array $definition A huge array defining all the declared instances definitions.
	 */
	public function addComponentInstances(array $definition) {
		$this->declaredInstances = array_merge($this->declaredInstances, $definition);
	}
	
	/**
	 * Declares a new component.
	 *
	 * @param string $instanceName
	 * @param string $className
	 * @param boolean $external Whether the component is external or not. Defaults to false.
	 * @param int $mode Depending on the mode, the behaviour will be different if an instance with the same name already exists.
	 * @param bool $weak If the object is weak, it will be destroyed if it is no longer referenced.
	 */
	public function declareComponent($instanceName, $className, $external = false, $mode = self::DECLARE_ON_EXIST_EXCEPTION, $weak = false) {
		if (isset($this->declaredInstances[$instanceName])) {
			if ($mode == self::DECLARE_ON_EXIST_EXCEPTION) {
				throw new MoufException("Unable to create Mouf istance named '".$instanceName."'. An instance with this name already exists.");
			} elseif ($mode == self::DECLARE_ON_EXIST_KEEP_INCOMING_LINKS) {
				$this->declaredInstances[$instanceName]["fieldProperties"] = array();
				$this->declaredInstances[$instanceName]["setterProperties"] = array();
				$this->declaredInstances[$instanceName]["fieldBinds"] = array();
				$this->declaredInstances[$instanceName]["setterBinds"] = array();
				$this->declaredInstances[$instanceName]["weak"] = $weak;
				$this->declaredInstances[$instanceName]["comment"] = "";
			} elseif ($mode == self::DECLARE_ON_EXIST_KEEP_ALL) {
				// Do nothing
			}
		}
		$this->declaredInstances[$instanceName]["class"] = $className;
		$this->declaredInstances[$instanceName]["external"] = $external;
	}
	
	/**
	 * Removes an instance.
	 * Sets to null any property linking to that component.
	 *
	 * @param string $instanceName
	 */
	public function removeComponent($instanceName) {
		unset($this->instanceDescriptors[$instanceName]);
		unset($this->declaredInstances[$instanceName]);
		foreach ($this->declaredInstances as $declaredInstanceName=>$declaredInstance) {
			if (isset($declaredInstance["fieldBinds"])) {
				foreach ($declaredInstance["fieldBinds"] as $paramName=>$properties) {
					if (is_array($properties)) {
						// If this is an array of properties
						$keys_matching = array_keys($properties, $instanceName);
						if (!empty($keys_matching)) {
							foreach ($keys_matching as $key) {
								unset($properties[$key]); 
							}
							$this->bindComponents($declaredInstanceName, $paramName, $properties);
						}
					} else {
						// If this is a simple property
						if ($properties == $instanceName) {
							$this->bindComponent($declaredInstanceName, $paramName, null);
						}
					}
				}
			}
		}

		foreach ($this->declaredInstances as $declaredInstanceName=>$declaredInstance) {
			if (isset($declaredInstance["setterBinds"])) {
				foreach ($declaredInstance["setterBinds"] as $setterName=>$properties) {
					if (is_array($properties)) {
						// If this is an array of properties
						$keys_matching = array_keys($properties, $instanceName);
						if (!empty($keys_matching)) {
							foreach ($keys_matching as $key) {
								unset($properties[$key]); 
							}
							$this->bindComponentsViaSetter($declaredInstanceName, $setterName, $properties);
						}
					} else {
						// If this is a simple property
						if ($properties == $instanceName) {
							$this->bindComponentViaSetter($declaredInstanceName, $setterName, null);
						}
					}
				}	
			}
		}
	}
	
	/**
	 * Renames an instance.
	 * All properties are redirected to the new instance accordingly.
	 *
	 * @param string $instanceName Old name
	 * @param string $instanceName New name
	 */
	public function renameComponent($instanceName, $newInstanceName) {
		if ($instanceName == $newInstanceName) {
			return;
		}
		
		if (isset($this->declaredInstances[$newInstanceName])) {
			throw new MoufException("Unable to rename instance '$instanceName' to '$newInstanceName': Instance '$newInstanceName' already exists.");
		}
		
		if (isset($this->declaredInstances[$instanceName]['external']) && $this->declaredInstances[$instanceName]['external'] == true) {
			throw new MoufException("Unable to rename instance '$instanceName' into '$newInstanceName': Instance '$instanceName' is declared externally.");
		}
		
		$this->declaredInstances[$newInstanceName] = $this->declaredInstances[$instanceName];
		unset($this->declaredInstances[$instanceName]);
		
		foreach ($this->declaredInstances as $declaredInstanceName=>$declaredInstance) {
			if (isset($declaredInstance["fieldBinds"])) {
				foreach ($declaredInstance["fieldBinds"] as $paramName=>$properties) {
					if (is_array($properties)) {
						// If this is an array of properties
						$keys_matching = array_keys($properties, $instanceName);
						if (!empty($keys_matching)) {
							foreach ($keys_matching as $key) {
								$properties[$key] = $newInstanceName;
							}
							$this->bindComponents($declaredInstanceName, $paramName, $properties);
						}
					} else {
						// If this is a simple property
						if ($properties == $instanceName) {
							$this->bindComponent($declaredInstanceName, $paramName, $newInstanceName);
						}
					}
				}
			}
		}
		
		foreach ($this->declaredInstances as $declaredInstanceName=>$declaredInstance) {
			if (isset($declaredInstance["setterBinds"])) {
				foreach ($declaredInstance["setterBinds"] as $setterName=>$properties) {
					if (is_array($properties)) {
						// If this is an array of properties
						$keys_matching = array_keys($properties, $instanceName);
						if (!empty($keys_matching)) {
							foreach ($keys_matching as $key) {
								$properties[$key] = $newInstanceName;
							}
							$this->bindComponentsViaSetter($declaredInstanceName, $setterName, $properties);
						}
					} else {
						// If this is a simple property
						if ($properties == $instanceName) {
							$this->bindComponentViaSetter($declaredInstanceName, $setterName, $newInstanceName);
						}
					}
				}	
			}
		}
	}
	
	/**
	 * Return the type of the instance.
	 *
	 * @param string $instanceName The instance name
	 * @return string The class name of the instance
	 */
	public function getInstanceType($instanceName) {
		return $this->declaredInstances[$instanceName]['class'];		
	}
	
	/**
	 * Instantiate the object (and any object needed on the way)
	 *
	 */
	private function instantiateComponent($instanceName) {
		if (!isset($this->declaredInstances[$instanceName])) {
			throw new MoufInstanceNotFoundException("The object instance '".$instanceName."' is not defined.", 1, $instanceName);
		}
		
		$instanceDefinition = $this->declaredInstances[$instanceName];

		$className = $instanceDefinition["class"];
		
		$object = new $className();
		$this->objectInstances[$instanceName] = $object;
		if (isset($instanceDefinition["fieldProperties"])) {
			foreach ($instanceDefinition["fieldProperties"] as $key=>$valueDef) {
				switch ($valueDef["type"]) {
					case "string":
						$object->$key = $valueDef["value"];
						break;
					case "request":
						$object->$key = $_REQUEST[$valueDef["value"]];
						break;
					case "session":
						$object->$key = $_SESSION[$valueDef["value"]];
						break;
					case "config":
						$object->$key = constant($valueDef["value"]);
						break;
					default:
						throw new MoufException("Invalid type '".$valueDef["type"]."' for object instance '$instanceName'.");
				}
			}
		}
		
		if (isset($instanceDefinition["setterProperties"])) {
			foreach ($instanceDefinition["setterProperties"] as $key=>$valueDef) {
				//$object->$key($valueDef["value"]);
				switch ($valueDef["type"]) {
					case "string":
						$object->$key($valueDef["value"]);
						break;
					case "request":
						$object->$key($_REQUEST[$valueDef["value"]]);
						break;
					case "session":
						$object->$key($_SESSION[$valueDef["value"]]);
						break;
					case "config":
						$object->$key(constant($valueDef["value"]));
						break;
					default:
						throw new MoufException("Invalid type '".$valueDef["type"]."' for object instance '$instanceName'.");
				}
			}
		}
		
		if (isset($instanceDefinition["fieldBinds"])) {
			foreach ($instanceDefinition["fieldBinds"] as $key=>$value) {
				if (is_array($value)) {
					$tmpArray = array();
					foreach ($value as $keyInstanceName=>$valueInstanceName) {
						$tmpArray[$keyInstanceName] = $this->getInstance($valueInstanceName);	
					}
					$object->$key = $tmpArray;
				} else {
					$object->$key = $this->getInstance($value);
				}
			}
		}
		
		if (isset($instanceDefinition["setterBinds"])) {
			foreach ($instanceDefinition["setterBinds"] as $key=>$value) {
				if (is_array($value)) {
					$tmpArray = array();
					foreach ($value as $keyInstanceName=>$valueInstanceName) {
						$tmpArray[$keyInstanceName] = $this->getInstance($valueInstanceName);	
					}
					$object->$key($tmpArray);
				} else {
					$object->$key($this->getInstance($value));
				}
			}
		}
		
		return $object;
	}

	/**
	 * Binds a parameter to the instance.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @param string $paramValue
	 * @param string $type Can be one of "string|config|request|session"
	 * @param array $metadata An array containing metadata
	 */
	public function setParameter($instanceName, $paramName, $paramValue, $type = "string", array $metadata = array()) {
		if ($type != "string" && $type != "config" && $type != "request" && $type != "session") {
			throw new MoufException("Invalid type. Must be one of: string|config|request|session. Value passed: '".$type."'");
		}
		
		$this->declaredInstances[$instanceName]["fieldProperties"][$paramName]["value"] = $paramValue;
		$this->declaredInstances[$instanceName]["fieldProperties"][$paramName]["type"] = $type;
		$this->declaredInstances[$instanceName]["fieldProperties"][$paramName]["metadata"] = $metadata;
	}
	
	/**
	 * Binds a parameter to the instance using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @param string $paramValue
	 * @param string $type Can be one of "string|config|request|session"
	 * @param array $metadata An array containing metadata
	 */
	public function setParameterViaSetter($instanceName, $setterName, $paramValue, $type = "string", array $metadata = array()) {
		if ($type != "string" && $type != "config" && $type != "request" && $type != "session") {
			throw new MoufException("Invalid type. Must be one of: string|config|request|session");
		}
		
		$this->declaredInstances[$instanceName]["setterProperties"][$setterName]["value"] = $paramValue;
		$this->declaredInstances[$instanceName]["setterProperties"][$setterName]["type"] = $type;
		$this->declaredInstances[$instanceName]["setterProperties"][$setterName]["metadata"] = $metadata;
	}
	
	/**
	 * Unsets all the parameters (using a property or a setter) for the given instance.
	 *
	 * @param string $instanceName The instance to consider
	 */
	public function unsetAllParameters($instanceName) {
		unset($this->declaredInstances[$instanceName]["fieldProperties"]);
		unset($this->declaredInstances[$instanceName]["setterProperties"]);
	}
	
	/**
	 * Returns the value for the given parameter.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @return mixed
	 */
	public function getParameter($instanceName, $paramName) {
		// todo: improve this
		if (isset($this->declaredInstances[$instanceName]['fieldProperties'][$paramName]['value'])) {
			return $this->declaredInstances[$instanceName]['fieldProperties'][$paramName]['value'];
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the value for the given parameter that has been set using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @return mixed
	 */
	public function getParameterForSetter($instanceName, $setterName) {
		// todo: improve this
		if (isset($this->declaredInstances[$instanceName]['setterProperties'][$setterName]['value'])) {
			return $this->declaredInstances[$instanceName]['setterProperties'][$setterName]['value'];
		} else {
			return null;
		}
	}

	/**
	 * Returns the type for the given parameter.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @return string
	 */
	public function getParameterType($instanceName, $paramName) {
		if (isset($this->declaredInstances[$instanceName]['fieldProperties'][$paramName]['type'])) {
			return $this->declaredInstances[$instanceName]['fieldProperties'][$paramName]['type'];
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the type for the given parameter that has been set using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @return string
	 */
	public function getParameterTypeForSetter($instanceName, $setterName) {
		if (isset($this->declaredInstances[$instanceName]['setterProperties'][$setterName]['type'])) {
			return $this->declaredInstances[$instanceName]['setterProperties'][$setterName]['type'];
		} else {
			return null;
		}
	}
	

	/**
	 * Returns the metadata for the given parameter.
	 * Metadata is an array of key=>value, containing additional info.
	 * For instance, it could contain information on the way to represent a field in the UI, etc...
	 * 
	 * @param string $instanceName
	 * @param string $paramName
	 * @return string
	 */
	public function getParameterMetadata($instanceName, $paramName) {
		if (isset($this->declaredInstances[$instanceName]['fieldProperties'][$paramName]['metadata'])) {
			return $this->declaredInstances[$instanceName]['fieldProperties'][$paramName]['metadata'];
		} else {
			return array();
		}
	}
	
	/**
	 * Returns the metadata for the given parameter that has been set using a setter.
	 * Metadata is an array of key=>value, containing additional info.
	 * For instance, it could contain information on the way to represent a field in the UI, etc...
	 * 
	 * @param string $instanceName
	 * @param string $setterName
	 * @return string
	 */
	public function getParameterMetadataForSetter($instanceName, $setterName) {
		if (isset($this->declaredInstances[$instanceName]['setterProperties'][$setterName]['metadata'])) {
			return $this->declaredInstances[$instanceName]['setterProperties'][$setterName]['metadata'];
		} else {
			return array();
		}
	}
	
	
	/**
	 * Returns true if the param is set for the given instance.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @return boolean
	 */
	public function hasParameter($instanceName, $paramName) {
		// todo: improve this
		return isset($this->declaredInstances[$instanceName]['fieldProperties'][$paramName]);
	}
	
	/**
	 * Returns true if the param is set for the given instance using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @return boolean
	 */
	public function hasParameterForSetter($instanceName, $setterName) {
		// todo: improve this
		return isset($this->declaredInstances[$instanceName]['setterProperties'][$setterName]);
	}
	
	/**
	 * Binds another instance to the instance.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @param string $paramValue the name of the instance to bind to.
	 */
	public function bindComponent($instanceName, $paramName, $paramValue) {
		if ($paramValue == null) {
			unset($this->declaredInstances[$instanceName]["fieldBinds"][$paramName]);
		} else {
			$this->declaredInstances[$instanceName]["fieldBinds"][$paramName] = $paramValue;
		}
	}
	
	/**
	 * Binds another instance to the instance via a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @param string $paramValue the name of the instance to bind to.
	 */
	public function bindComponentViaSetter($instanceName, $setterName, $paramValue) {
		if ($paramValue == null) {
			unset($this->declaredInstances[$instanceName]["setterBinds"][$setterName]);
		} else {
			$this->declaredInstances[$instanceName]["setterBinds"][$setterName] = $paramValue;
		}
	}
	
	/**
	 * Binds an array of instance to the instance.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @param array $paramValue an array of names of instance to bind to.
	 */
	public function bindComponents($instanceName, $paramName, $paramValue) {
		if ($paramValue == null) {
			unset($this->declaredInstances[$instanceName]["fieldBinds"][$paramName]);
		} else {
			$this->declaredInstances[$instanceName]["fieldBinds"][$paramName] = $paramValue;
		}
	}

	/**
	 * Binds an array of instance to the instance via a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @param array $paramValue an array of names of instance to bind to.
	 */
	public function bindComponentsViaSetter($instanceName, $setterName, $paramValue) {
		if ($paramValue == null) {
			unset($this->declaredInstances[$instanceName]["setterBinds"][$setterName]);
		} else {
			$this->declaredInstances[$instanceName]["setterBinds"][$setterName] = $paramValue;
		}
	}	

	/**
	 * This simply adds the passed file to the list of "registered components".
	 * The list will be required by mouf.php when it is generated using "rewriteMouf" function.
	 * It s possible to add an autoload parameter to force, never or auto load the file
	 *
	 * @param string $fileName
	 * @param string $autoload
	 */
	public function registerComponent($fileName, $autoload = 'auto') {
		$this->registeredComponents[$fileName] = array('name' => $fileName, 'autoload' => $autoload);
	}
	
	/**
	 * This function will rewrite the Mouf.php file according to parameters stored in the MoufManager
	 * TODO: protect special characters!!!!
	 */
	public function rewriteMouf() {
		if (!is_writable(dirname(dirname(__FILE__)."/".$this->componentsFileName)) || (file_exists(dirname(__FILE__)."/".$this->componentsFileName) && !is_writable(dirname(__FILE__)."/".$this->componentsFileName))) {
			$dirname = realpath(dirname(dirname(__FILE__)."/".$this->componentsFileName));
			$filename = basename(dirname(__FILE__)."/".$this->componentsFileName);
			throw new MoufException("Error, unable to write file ".$dirname."/".$filename);
		}
		
		if (!is_writable(dirname(dirname(__FILE__)."/".$this->requireFileName)) || (file_exists(dirname(__FILE__)."/".$this->requireFileName) && !is_writable(dirname(__FILE__)."/".$this->requireFileName))) {
			$dirname = realpath(dirname(dirname(__FILE__)."/".$this->requireFileName));
			$filename = basename(dirname(__FILE__)."/".$this->requireFileName);
			throw new MoufException("Error, unable to write file ".$dirname."/".$filename);
		}
		
		// Let's start by garbage collecting weak instances.
		$this->purgeUnreachableWeakInstances();
		
		$fp = fopen(dirname(__FILE__)."/".$this->componentsFileName, "w");
		fwrite($fp, "<?php\n");
		fwrite($fp, "/**\n");
		fwrite($fp, " * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.\n");
		fwrite($fp, " */\n");
		fwrite($fp, "require_once '".$this->pathToMouf."MoufManager.php';\n");
		fwrite($fp, "MoufManager::initMoufManager();\n");
		fwrite($fp, "\$moufManager = MoufManager::getMoufManager();\n");
		fwrite($fp, "\n");
		fwrite($fp, "\$moufManager->getConfigManager()->setConstantsDefinitionArray(".var_export($this->getConfigManager()->getConstantsDefinitionArray(), true).");\n");
		fwrite($fp, "\n");
		
		// Declare packages
		fwrite($fp, "\$moufManager->setPackagesByXmlFile(".var_export($this->packagesList, true).");\n");
		fwrite($fp, "\n");
		
		fwrite($fp, "\$moufManager->setPackagesByXmlFileInAdminScope(".var_export($this->packagesListInAdminScope, true).");\n");
		fwrite($fp, "\n");
		
		// Import external components
		$packageManager = new MoufPackageManager(dirname(__FILE__).'/../plugins');
		foreach ($this->packagesList as $fileName) {
			$package = $packageManager->getPackage($fileName);
			foreach ($package->getExternalComponentsRequiredFiles() as $requiredFile) {
				// Please notice that this is a require and not a require_once.
				// This is because the file could be required twice: one for Mouf and one for the admin.
				// Therefore, the file should not declare functions or classes.
				fwrite($fp, "require dirname(__FILE__).'/".$this->pathToMouf."../plugins/".$package->getPackageDirectory()."/".$requiredFile."';\n");
			}
		}
		fwrite($fp, "\n");

		// Import all variables
		fwrite($fp, "\$moufManager->setAllVariables(".var_export($this->variables, true).");\n");
		fwrite($fp, "\n");
		
		// Declare all components in one instruction
		$internalDeclaredInstances = array();
		foreach ($this->declaredInstances as $name=>$declaredInstance) {
			if (!isset($declaredInstance["external"]) || !$declaredInstance["external"]) {
				$internalDeclaredInstances[$name] = $declaredInstance;
			}
		}
		
		// Sort all instances by key. This way, new instances are not added at the end of the array,
		// and this reduces the number of conflicts when working in team with a version control system.
		ksort($internalDeclaredInstances);
		
		fwrite($fp, "\$moufManager->addComponentInstances(".var_export($internalDeclaredInstances, true).");\n");
		fwrite($fp, "\n");
		
		// Declare local components
		foreach ($this->registeredComponents as $registeredComponent => $registeredComponentParameters) {
			//fwrite($fp, "require_once dirname(__FILE__).'/$registeredComponent';\n");
			$autoload = 'auto';
			if(isset($registeredComponentParameters['autoload']))
				$autoload = $registeredComponentParameters['autoload'];
			fwrite($fp, "\$moufManager->registerComponent('$registeredComponent', '".$autoload."');\n");
		}
		fwrite($fp, "\n");
		
		fwrite($fp, "unset(\$moufManager);\n");
		fwrite($fp, "\n");
		
		fwrite($fp, "/**
 * This is the base class of the Manage Object User Friendly or Modular object user framework (MOUF) framework.
 * This object can be used to get the objects manage by MOUF.
 *
 */
class ".$this->mainClassName." {
");
		
		foreach ($this->declaredInstances as $name=>$classDesc) {
			$className = $classDesc['class'];
			//if (!isset($this->externalComponents[$name]) || $this->externalComponents[$name] != true) {
				fwrite($fp, "	/**\n");
				fwrite($fp, "	 * @return $className\n");
				fwrite($fp, "	 */\n");
				fwrite($fp, "	 public static function ".self::generateGetterString($name)."() {\n");
				fwrite($fp, "	 	return MoufManager::getMoufManager()->getInstance(".var_export($name,true).");\n");
				fwrite($fp, "	 }\n\n");
			//}
		}
		fwrite($fp, "}\n");
		
		
		fwrite($fp, "?>\n");
		fclose($fp);
		
		// Analyze includes to manage autoloadable files.
		$selfEdit = ($this->scope == MoufManager::SCOPE_ADMIN); 
		$analyzeResults = MoufReflectionProxy::analyzeIncludes($selfEdit);

		$autoloadableFiles = array();
		$classesFiles = array();

		// If no error in the curl analyze
		if (!isset($analyzeResults['errorType'])) {
			// Packages
			foreach ($analyzeResults['packages']["classes"] as $file => $classes) {
				if($classes)
					$autoloadableFiles[$file] = true;
			}
			foreach ($analyzeResults['packages']["interfaces"] as $file => $interfaces) {
				if($interfaces)
					$autoloadableFiles[$file] = true;
			}
			foreach ($analyzeResults['packages']["functions"] as $file => $functions) {
				if($functions)
					$autoloadableFiles[$file] = false;
			}
		
			// Check the configuration of package, it is possible some require must never or force autoload
			foreach ($this->packagesList as $fileName) {
				$package = $packageManager->getPackage($fileName);
				$packagePath = $this->pathToMouf."../plugins/".$package->getPackageDirectory().'/';
				foreach ($package->getRequiredFilesParameters() as $requiredFile => $requiredFileParameters) {
					if(isset($requiredFileParameters['autoload'])) {
						if($requiredFileParameters['autoload'] == 'never') {
							$autoloadableFiles[$packagePath.$requiredFile] = false;
						}
						elseif($requiredFileParameters['autoload'] == 'force') {
							$autoloadableFiles[$packagePath.$requiredFile] = true;
						}
					}
				}
			}
			
			// Array to associate class with file
			foreach ($analyzeResults['packages']["classes"] as $file => $classes) {
				if(isset($autoloadableFiles[$file]) && $autoloadableFiles[$file]) {
					foreach ($classes as $class) {
						$classesFiles[$class] = $file;
					}
				}
			}
		
			foreach ($analyzeResults['packages']["interfaces"] as $file => $interfaces) {
				if(isset($autoloadableFiles[$file]) && $autoloadableFiles[$file]) {
					foreach ($interfaces as $interface) {
						$classesFiles[$interface] = $file;
					}
				}
			}
			
			// Requested files
			foreach ($analyzeResults["classes"] as $file => $classes) {
				if($classes)
					$autoloadableFiles[$file] = true;
			}
			foreach ($analyzeResults["interfaces"] as $file => $interfaces) {
				if($interfaces)
					$autoloadableFiles[$file] = true;
			}
			foreach ($analyzeResults["functions"] as $file => $functions) {
				if($functions)
					$autoloadableFiles[$file] = false;
			}
		
			// Check the configuration of require, it s possible some require must never or force autoload
			foreach ($this->registeredComponents as $registeredComponent => $registeredComponentParameters) {
				if(isset($registeredComponentParameters['autoload'])) {
					if($registeredComponentParameters['autoload'] == 'never') {
						$autoloadableFiles[$registeredComponent] = false;
					}
					elseif($registeredComponentParameters['autoload'] == 'force') {
						$autoloadableFiles[$registeredComponent] = true;
					}
				}
			}
			
			// Array to associate class with file
			foreach ($analyzeResults["classes"] as $file => $classes) {
				if(isset($autoloadableFiles[$file]) && $autoloadableFiles[$file]) {
					foreach ($classes as $class) {
						$classesFiles[$class] = $file;
					}
				}
			}
		
			foreach ($analyzeResults["interfaces"] as $file => $interfaces) {
				if(isset($autoloadableFiles[$file]) && $autoloadableFiles[$file]) {
					foreach ($interfaces as $interface) {
						$classesFiles[$interface] = $file;
					}
				}
			}
		}
		
		// Finally, let's add Mouf classes to the list of autoloadable classes:
		$classesFiles['MoufInstanceDescriptor']='mouf/MoufInstanceDescriptor.php';
		$classesFiles['MoufInstancePropertyDescriptor']='mouf/MoufInstancePropertyDescriptor.php';
		$classesFiles['MoufPropertyDescriptor']='mouf/MoufPropertyDescriptor.php';
		$classesFiles['varAnnotation']='mouf/annotations/varAnnotation.php';
		$classesFiles['paramAnnotation']='mouf/annotations/paramAnnotation.php';
		
		// Now, let's write the MoufRequire file that contains all the "require_once" stuff.
		$fp2 = fopen(dirname(__FILE__)."/".$this->requireFileName, "w");
		fwrite($fp2, "<?php\n");
		fwrite($fp2, "/**\n");
		fwrite($fp2, " * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.\n");
		fwrite($fp2, " */\n");
		fwrite($fp2, "\n");

		fwrite($fp2, "// Register autoloadable classes\n");
		fwrite($fp2, 'MoufManager::getMoufManager()->registerAutoloadedClasses('.var_export($classesFiles, true).');'."\n");
		fwrite($fp2, 'spl_autoload_register(array(MoufManager::getMoufManager(), "autoload"));'."\n");
		
		// Let's add the require_once from the packages first!
		fwrite($fp2, "// Packages dependencies\n");
		fwrite($fp2, "\$localFilePath = dirname(__FILE__);\n");
		foreach ($this->packagesList as $fileName) {
			$package = $packageManager->getPackage($fileName);
			foreach ($package->getRequiredFiles() as $requiredFile) {
				$pathFromRootPath = $this->pathToMouf."../plugins/".$package->getPackageDirectory()."/".$requiredFile;
				if(((!isset($autoloadableFiles[$pathFromRootPath]) || !$autoloadableFiles[$pathFromRootPath])
						&& $package->getAutoloadRequiredFile($requiredFile) == 'auto')
					|| $package->getAutoloadRequiredFile($requiredFile) == 'never') {
					fwrite($fp2, "require_once \$localFilePath.'/".$pathFromRootPath."';\n");
				}
			}
		}
		fwrite($fp2, "\n");
		
		fwrite($fp2, "// User dependencies\n");
		
		foreach ($this->registeredComponents as $registeredComponent => $registeredComponentParameters) {
			if(!isset($autoloadableFiles[$registeredComponent]) || !$autoloadableFiles[$registeredComponent]) {
				fwrite($fp2, "require_once \$localFilePath.'/$registeredComponent';\n");
			}
		}
		fwrite($fp2, "\n");
		
		
		
		fwrite($fp2, "?>\n");
		fclose($fp2);

		// Now, let's write the MoufUI file that contains all the "require_once" stuff for the admin part of Mouf.
		$fp3 = fopen(dirname(__FILE__)."/".$this->adminUiFileName, "w");
		fwrite($fp3, "<?php\n");
		fwrite($fp3, "/**\n");
		fwrite($fp3, " * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.\n");
		fwrite($fp3, " */\n");
		fwrite($fp3, "\n");
		
		fwrite($fp3, "// Files from packages declared in the admin <scope/>.\n");
		foreach ($this->packagesListInAdminScope as $fileName) {
			$package = $packageManager->getPackage($fileName);
			foreach ($package->getRequiredFiles() as $requiredFile) {
				fwrite($fp3, "require_once dirname(__FILE__).'/".$this->pathToMouf."../plugins/".$package->getPackageDirectory()."/".$requiredFile."';\n");
			}
		}
		
		fwrite($fp3, "\n");
		
		fwrite($fp3, "// Files declared in the <adminRequire/> section.\n");
		foreach ($this->packagesList as $fileName) {
			$package = $packageManager->getPackage($fileName);
			foreach ($package->getAdminRequiredFiles() as $requiredFile) {
				fwrite($fp3, "require_once dirname(__FILE__).'/".$this->pathToMouf."../plugins/".$package->getPackageDirectory()."/".$requiredFile."';\n");
			}
		}
		fwrite($fp3, "\n");
		
		
		
		fwrite($fp3, "?>\n");
		fclose($fp3);
		
	}
	
	/**
	 * Generate the string for the getter by uppercasing the first character and prepending "get".
	 *
	 * @param string $instanceName
	 * @return string
	 */
	private function generateGetterString($instanceName) {
		$modInstance = str_replace(" ", "", $instanceName);
		$modInstance = str_replace("\n", "", $modInstance);
		$modInstance = str_replace("-", "", $modInstance);
		$modInstance = str_replace(".", "_", $modInstance);
		// Let's remove anything that is not an authorized character:
		$modInstance = preg_replace("/[^A-Za-z0-9_]/", "", $modInstance);
		
		
		return "get".strtoupper(substr($modInstance,0,1)).substr($modInstance,1);
	}
	
	/**
	 * Return all instances names whose instance type is (or extends or inherits) the provided $instanceType
	 *
	 * @param string $instanceType
	 * @return array<string>
	 */
	public function findInstances($instanceType) {
		$instancesArray = array();
		foreach ($this->declaredInstances as $instanceName=>$classDesc) {
			$className = $classDesc['class'];
			$obj = new $className();
			//if (is_a($obj, $instanceType)) {
			if ($obj instanceof $instanceType) {
				$instancesArray[] = $instanceName;
			}
		}
		return $instancesArray;		
	}
	
	/**
	 * Returns the name(s) of the component bound to instance $instanceName on property $propertyName.
	 *
	 * @param string $instanceName
	 * @param string $propertyName
	 * @return string or array<string> if there are many components.
	 */
	public function getBoundComponentsOnProperty($instanceName, $propertyName) {
		if (isset($this->declaredInstances[$instanceName]) && isset($this->declaredInstances[$instanceName]['fieldBinds']) && isset($this->declaredInstances[$instanceName]['fieldBinds'][$propertyName])) {
			return $this->declaredInstances[$instanceName]['fieldBinds'][$propertyName];
		}
		else
			return null;
	}
	
	/**
	 * Returns the name(s) of the component bound to instance $instanceName on setter $setterName.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @return string or array<string> if there are many components.
	 */
	public function getBoundComponentsOnSetter($instanceName, $setterName) {
		if (isset($this->declaredInstances[$instanceName]) && isset($this->declaredInstances[$instanceName]['setterBinds']) && isset($this->declaredInstances[$instanceName]['setterBinds'][$setterName]))
			return $this->declaredInstances[$instanceName]['setterBinds'][$setterName];
		else
			return null;
	}
	
	/**
	 * Returns the list of all components bound to that component. 
	 *
	 * @param string $instanceName
	 * @return array<string, comp(s)> where comp(s) is a string or an array<string> if there are many components for that property. The key of the array is the name of the property. 
	 */
	public function getBoundComponents($instanceName) {
		$binds = array();
		if (isset($this->declaredInstances[$instanceName]) && isset($this->declaredInstances[$instanceName]['fieldBinds'])) {
			$binds = $this->declaredInstances[$instanceName]['fieldBinds'];
		}
		if (isset($this->declaredInstances[$instanceName]) && isset($this->declaredInstances[$instanceName]['setterBinds'])) {
			foreach ($this->declaredInstances[$instanceName]['setterBinds'] as $setter=>$bind) {
				$binds[MoufPropertyDescriptor::getPropertyNameFromSetterName($setter)] = $bind;
			}
		}
		return $binds;		
	}
	
	/**
	 * Returns the list of instances that are pointing to this instance through one of their properties.
	 *
	 * @param string $instanceName
	 * @return array<string, string> The instances pointing to the passed instance are returned in key and in the value
	 */
	public function getOwnerComponents($instanceName) {
		$instancesList = array();
		
		foreach ($this->declaredInstances as $scannedInstance=>$instanceDesc) {
			if (isset($instanceDesc['fieldBinds'])) {
				foreach ($instanceDesc['fieldBinds'] as $declaredBindProperty) {
					if (is_array($declaredBindProperty)) {
						if (array_search($instanceName, $declaredBindProperty) !== false) {
							$instancesList[$scannedInstance] = $scannedInstance;
							break;
						}
					} elseif ($declaredBindProperty == $instanceName) {
						$instancesList[$scannedInstance] = $scannedInstance;
					}
				}
			}
		}
		
		foreach ($this->declaredInstances as $scannedInstance=>$instanceDesc) {
			if (isset($instanceDesc['setterBinds'])) {
				foreach ($instanceDesc['setterBinds'] as $declaredBindProperty) {
					if (is_array($declaredBindProperty)) {
						if (array_search($instanceName, $declaredBindProperty) !== false) {
							$instancesList[$scannedInstance] = $scannedInstance;
							break;
						}
					} elseif ($declaredBindProperty == $instanceName) {
						$instancesList[$scannedInstance] = $scannedInstance;
					}
				}
			}
		}
		
		return $instancesList;
		
	}
	
	/**
	 * Returns the list of files that will be included by Mouf, relative to the root of the project
	 *
	 * @return array<string>
	 */
	public function getRegisteredComponentFiles() {
		
		$fileArray = array();
		$dirMoufFile = dirname($this->requireFileName);
		$fulldir = realpath(dirname(__FILE__)."/..");
		foreach ($this->registeredComponents as $file => $registeredComponentParameters) {
			$realpathFile = realpath($dirMoufFile."/".$file);
			$relativeFile = substr($realpathFile, strlen($fulldir)+1);
			$relativeFile = str_replace("\\", "/", $relativeFile);
			$fileArray[] = $relativeFile;
		}
		
		return $fileArray;
	}
	
	/**
	 * Returns the list of files that will be included by Mouf, relative to the root of the project
	 * Add parameters of files like autoload
	 *
	 * @return array<string, array<string, string>>
	 */
	public function getRegisteredComponentFilesParameters() {
		
		$fileArray = array();
		$dirMoufFile = dirname($this->requireFileName);
		$fulldir = realpath(dirname(__FILE__)."/..");
		foreach ($this->registeredComponents as $file => $registeredComponentParameters) {
			$realpathFile = realpath($dirMoufFile."/".$file);
			$relativeFile = substr($realpathFile, strlen($fulldir)+1);
			$relativeFile = str_replace("\\", "/", $relativeFile);
			$fileArray[$relativeFile] = $registeredComponentParameters;
		}
		
		return $fileArray;
	}
	
	/**
	 * Sets a list of files that will be included by Mouf, relative to the root of the project.
	 *
	 * @param array<string> $files
	 * @param array<string, string> $autoloads List of files in index and autoload value
	 */
	public function setRegisteredComponentFiles($files, $autoloads = array()) {
		
		$dirMoufFile = dirname(dirname(__FILE__)."/".$this->requireFileName);
		$fulldir = realpath(dirname(__FILE__)."/../");
		$fulldir = str_replace("\\", "/", $fulldir);
		// Depending on the version of PHP, we might or might not have a trailing /. Let's add it.
		if (substr($fulldir, -1) != "/" ) {
			$fulldir .= "/";
		}

		$registeredComponentsFile = array();
		foreach ($files as $file) {
			$fileFull = $fulldir.$file;
			$path = $this->createRelativePath($dirMoufFile, $fileFull);
			$autoload = 'auto';
			if(isset($autoloads[$file]))
				$autoload = $autoloads[$file];
			$registeredComponentsFile[$path] = array('name' => $path, 'autoload' => $autoload);
		}

		$this->registeredComponents = $registeredComponentsFile;
	}

	/**
	 * Adds one file that will be included by Mouf, relative to the root of the project.
	 * 
	 * @param string $file
	 * @param string $autoload Autoload parameter, auto by default
	 */
	public function addRegisteredComponentFile($file, $autoload = 'auto') {
		$dirMoufFile = dirname(dirname(__FILE__)."/".$this->requireFileName);
		$fulldir = realpath(dirname(__FILE__)."/../");
		$fulldir = str_replace("\\", "/", $fulldir);
		// Depending on the version of PHP, we might or might not have a trailing /. Let's add it.
		if (substr($fulldir, -1) != "/" ) {
			$fulldir .= "/";
		}

		$fileFull = $fulldir.$file;
		if (array_key_exists($file, $this->registeredComponents) === false) {
			$path = $this->createRelativePath($dirMoufFile, $fileFull);
			$this->registeredComponents[$path] = array('name' => $path, 'autoload' => $autoload);
		}		
	}
	
	/**
	 * Creates a relative path from the directory $fromDirectory (current dir) to the file $toFile.
	 *
	 * @param unknown_type $fromDirectory
	 * @param unknown_type $toFile
	 */
	private function createRelativePath($fromDirectory, $toFile) {
		
		$realPathFromDir = str_replace("\\", "/", realpath($fromDirectory)) ;
		$realPathToFile = str_replace("\\", "/", realpath($toFile));
		// Let's find the common root by going through each directory.
		
		$realPathFromDirArray = explode("/", $realPathFromDir);
		$realPathToFileArray = explode("/", $realPathToFile);

		while (!empty($realPathFromDirArray) && !empty($realPathToFileArray) && $realPathFromDirArray[0] == $realPathToFileArray[0]) {
			array_shift($realPathFromDirArray);
			array_shift($realPathToFileArray);
		}
		
		$nbDirsRemaining = count($realPathFromDirArray);
		
		
		$path = str_repeat("../", $nbDirsRemaining).implode("/", $realPathToFileArray);
		return $path;
	}
	
	/**
	 * Adds a package by providing the path to the package.xml file.
	 * The path is relative to the "plugins" directory. 
	 *
	 * Deprecated. Should not be used.
	 * FOR BACKWARD COMPATIBILITY ONLY
	 *
	 * @param string $fileName
	 */
	public function addPackageByXmlFile($fileName) {
		$this->packagesList[] = $fileName;
	}
	
	/**
	 * Adds a package by providing the path to the package.xml file.
	 * The path is relative to the "plugins" directory.
	 * A complete check is performed.
	 * If another version of the package is present, the version of the package is replaced.
	 * This function also ensures the order of the dependencies is correct. 
	 *
	 * @param string $fileName
	 */
	public function addPackageByXmlFileWithCheck($fileName, $scope = "app") {
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!
		// TODO: TAKE IN PARAMETER THE SCOPE!!!!

		$myPackageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($fileName);
		
		
		// If we are supposed to add an admin package from the app scope, this means the admin package is added from tha app mode as an "admin" scope dependency
		if ($this->scope == MoufManager::SCOPE_APP && $scope == MoufManager::SCOPE_ADMIN) {
			// Let's first check if the package to install is not one of Mouf admin core packages.
			// If so, we cannot change it, so we should fail:
			$packagesList = MoufManager::getMoufManager()->listEnabledPackagesXmlFiles(false);
			foreach ($packagesList as $key=>$packageFileName) {
				$packageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageFileName);
				 
				if ($packageDescriptor->getGroup() == $myPackageDescriptor->getGroup() && $packageDescriptor->getName() == $myPackageDescriptor->getName()) {
				 	// We must replace this package but we can't!
					throw new MoufException("Problem! Cannot install ".$myPackageDescriptor->getGroup()."/".$myPackageDescriptor->getName()."/".$myPackageDescriptor->getVersion().". The Mouf admin includes by default this package in version ".$packageDescriptor->getVersion());
				}				 
			}
			
			// Now, let's see if we should replace any additional package (this is ok).
			$replaced = false;
			foreach ($this->packagesListInAdminScope as $key=>$packageFileName) {
				$packageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageFileName);
				 
				if ($packageDescriptor->getGroup() == $myPackageDescriptor->getGroup() && $packageDescriptor->getName() == $myPackageDescriptor->getName()) {
				 	// We must replace this package!
					$this->packagesListInAdminScope[$key] = $fileName;
					$replaced = true;
					break;
				}				 
			}
			
			if (!$replaced) {
				$this->packagesListInAdminScope[] = $fileName;	
			}
			
			// TODO: add reorderpackages support for the $packagesListInAdminScope
			return;
		}
		
		$packageDescriptors = array();
		
		// Whether the package replaces an existing package or not.
		$replaced = false;
		
		foreach ($this->packagesList as $key=>$packageFileName) {
			$packageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageFileName);
			 
			if ($packageDescriptor->getGroup() == $myPackageDescriptor->getGroup() && $packageDescriptor->getName() == $myPackageDescriptor->getName()) {
			 	// We must replace this package!
				$this->packagesList[$key] = $fileName;
				$replaced = true;
				break;
			}
			 
		}
		
		if (!$replaced) {
			$this->packagesList[] = $fileName;	
		}
		
		$this->reorderPackagesDependencies();
	}
	
	/**
	 * This function sorts the packages according to their dependency order.
	 */
	public function reorderPackagesDependencies() {
		
		$packagesXmlFiles = $this->listEnabledPackagesXmlFiles();
		// In case some packages are there twice (this should never happen unless the user plays with the MoufComponents file).
		$packagesXmlFiles = array_unique($packagesXmlFiles);
		
		// As long as there are packages to be reordered...
		while ($reorderDescriptor = $this->checkPackageOrder()) {
			$parentPackage = $reorderDescriptor["parentPackage"];
			/* @var $parentPackage MoufPackage */
			$parentPackageXmlFile = $parentPackage->getDescriptor()->getGroup()."/".$parentPackage->getDescriptor()->getName()."/".$parentPackage->getDescriptor()->getVersion()."/package.xml";
			
			$tooLateListPackageXmlFiles = array();
			foreach ($reorderDescriptor["tooLateChildren"] as $tooLateListPackage) {
				/* @var $tooLateListPackage MoufPackageDescriptor */
				$tooLateListPackageXmlFiles[] = $tooLateListPackage->getGroup()."/".$tooLateListPackage->getName()."/".$tooLateListPackage->getVersion()."/package.xml";
			}
			
			$newPackagesXmlFilesList = array();
			foreach ($packagesXmlFiles as $packageXmlFile) {
				if ($packageXmlFile != $parentPackageXmlFile && array_search($packageXmlFile, $tooLateListPackageXmlFiles) === false ) {
					$newPackagesXmlFilesList[] = $packageXmlFile;
				}
				if ($packageXmlFile == $parentPackageXmlFile) {
					foreach ($tooLateListPackageXmlFiles as $tooLateListPackageXmlFile) {
						$newPackagesXmlFilesList[] = $tooLateListPackageXmlFile;
					}
					$newPackagesXmlFilesList[] = $parentPackageXmlFile;
				}
			}
			$packagesXmlFiles = $newPackagesXmlFilesList;
			$this->setPackagesByXmlFile($packagesXmlFiles);
		}
	}
	
	/**
	 * This function checks the order of the packages.
	 * It can return a list of packages that should be reordered.
	 * Returns false if everything is alright.
	 * 
	 * @return array("parentPackage"=>MoufPackage, "tooLateList"=>array(MoufPackage))
	 */
	private function checkPackageOrder() {

		$packagesXmlFiles = $this->listEnabledPackagesXmlFiles();

		$errorList = array();
		$tooLateList = array();
		
		foreach ($packagesXmlFiles as $packageXmlFile) {
			$packageManager = new MoufPackageManager($this->getFullPathToPluginsDirectory());
			//$packageManager = new MoufPackageManager(dirname(__FILE__)."/../plugins");
			
			$package = $packageManager->getPackage($packageXmlFile);
			$dependencies = $package->getDependenciesAsDescriptors();
			
			$found = false;
			foreach ($dependencies as $dependency) {
				$tooLate = false;
				/* @var $dependency MoufDependencyDescriptor */
				// Let's test if each dependency is available, and in the first part of the dependencies.
				foreach ($packagesXmlFiles as $packageXmlFileCheck) {
					if ($packageXmlFileCheck == $packageXmlFile) {
						// After current package, we are too late, we should change the order of the packages. 
						$tooLate = true;
					}
					
					$installedPackageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageXmlFileCheck);
					if ($dependency->getGroup() == $installedPackageDescriptor->getGroup()
						&& $dependency->getName() == $installedPackageDescriptor->getName()) {
						if (!$dependency->isCompatibleWithVersion($installedPackageDescriptor->getVersion())) {
							// Let's just ignore incompatible package.
							// This method is about checking the order
						} else {
							if ($tooLate) {
								$tooLateList['parentPackage'] = $package;
								$tooLateList['tooLateChildren'][] = $installedPackageDescriptor;
							} else {
								$found = true;
							}
						}
					}
				}
				
				if (!$found) {
					// Let's just ignore not found packages.
					// This method is about checking the order
				} else {
					$found = false;
				}
			}
			if ($tooLateList) {
				return $tooLateList; 
			}
		}
		return false;
	}
	
	/**
	 * Set the packages list all at once.
	 * This function does not perform any verifications. It is used in MoufComponents.php to load all the
	 * package list quickly, at once.
	 * 
	 * @param array $fileNameArray
	 */
	public function setPackagesByXmlFile($fileNameArray) {
		$this->packagesList = $fileNameArray;
	}
	
	/**
	 * Set the packages list that must be loaded in admin scope all at once.
	 * This function does not perform any verifications. It is used in MoufComponents.php to load all the
	 * package list quickly, at once.
	 * 
	 * @param array $fileNameArray
	 */
	public function setPackagesByXmlFileInAdminScope($fileNameArray) {
		$this->packagesListInAdminScope = $fileNameArray;
	}
	
	
	/**
	 * Removes a package by providing the path to the package.xml file.
	 * The path is relative to the "plugins" directory. 
	 *
	 * @param string $fileName
	 */
	public function removePackageByXmlFile($fileName) {
		$key = array_search($fileName, $this->packagesList);
		unset($this->packagesList[$key]);
	}
	
	/**
	 * Returns true if the package is enabled.
	 * The path provided should by related to "plugins" directory. 
	 *
	 * @param string $fileName
	 */
	public function isPackageEnabled($fileName) {
		if (array_search($fileName, $this->packagesList) !== false) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns the version number enabled for the package passed in parameter,
	 * or null if the package is not enabled.
	 * 
	 * @param string $group
	 * @param string $name
	 */
	public function getVersionForEnabledPackage($group, $name) {
		$packageList = $this->listEnabledPackagesXmlFiles(true);
		foreach ($packageList as $packageFileName) {
			$packageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageFileName);
			if ($group == $packageDescriptor->getGroup() && $name == $packageDescriptor->getName()) {
				return $packageDescriptor->getVersion();
			}
		}
		return null;
	}
	
	/**
	 * Returns the path to all the packages.xml files that are enabled, as a list of strings.
	 * 
	 * @param bool $includeAdminScope For the SCOPE_ADMIN MoufManager, if $includeAdminScope=true, the list will return packages that are loaded in admin because of a dependency.
	 * @return array<string>
	 */
	public function listEnabledPackagesXmlFiles($includeAdminScope = false) {
		if (!$includeAdminScope || $this->scope == MoufManager::SCOPE_APP) {
			return $this->packagesList;
		}
		$packageList = $this->packagesList;
		// Get the addtional packages list from the APP scope.
		$additionalPackageList = MoufManager::getMoufManager()->packagesListInAdminScope;
		return array_merge($packageList, $additionalPackageList);
	}
	
	/**
	 * Returns the name of a Mouf instance from the object.
	 * Note: this quite be pretty slow as all instances are searched.
	 * FALSE is returned if nothing is found.
	 * 
	 * @param object $instance
	 * @return string The name of the instance.
	 */
	public function findInstanceName($instance) {
		return array_search($instance, $this->objectInstances);
	}
	
	/**
	 * Returns the full path to the plugins directory.
	 *
	 * @return string
	 */
	public function getFullPathToPluginsDirectory() {
		//return dirname(__FILE__)."/../".$this->pathToMouf."../plugins";
		return dirname(__FILE__)."/../plugins";
	}
	
	/**
	 * Duplicates an instance.
	 *
	 * @param string $srcInstanceName The name of the source instance.
	 * @param string $destInstanceName The name of the new instance.
	 */
	public function duplicateInstance($srcInstanceName, $destInstanceName) {
		if (!isset($this->declaredInstances[$srcInstanceName])) {
			throw new MoufException("Error while duplicating instance: unable to find source instance ".$srcInstanceName);
		}
		if (isset($this->declaredInstances[$destInstanceName])) {
			throw new MoufException("Error while duplicating instance: the dest instance already exists: ".$destInstanceName);
		}
		$this->declaredInstances[$destInstanceName] = $this->declaredInstances[$srcInstanceName];
	}
	
	/**
	 * Returns the list of files to be included because of the packages.
	 * This function can be useful to analyze the Mouf includes.
	 * @return array<string>
	 */
	public function getFilesListRequiredByPackages() {
		$files = array();
		$packageManager = new MoufPackageManager($this->getFullPathToPluginsDirectory());
		foreach ($this->packagesList as $fileName) {
			$package = $packageManager->getPackage($fileName);
			foreach ($package->getRequiredFiles() as $requiredFile) {
				$files[] = $this->pathToMouf."../plugins/".$package->getPackageDirectory()."/".$requiredFile;
			}
		}
		return $files;
	}
	
	/**
	 * This function performs a check to see if all packages that we are referencing are indeed available.
	 * If not, the function returns an array with the list of missing packages (as packages descriptor).
	 * 
	 * @return array<MoufPackageDescriptor>
	 */
	public function getMissingPackages() {
		$pluginsDir = $this->getFullPathToPluginsDirectory();
		$missingPackages = array();
		foreach ($this->packagesList as $packageFile) {
			if (!is_readable($pluginsDir."/".$packageFile)) {
				$missingPackages[] = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageFile);
			}
		}
		return $missingPackages;
	}
	
	/**
	 * Returns the list of files to be required (relative to the directory of Mouf.php)
	 *
	 * @return array<string>
	 */
	public function getRegisteredIncludeFiles() {
		$return = array();
		foreach ($this->registeredComponents as $registeredComponent => $registeredComponentParameters) {
			$return[] = $registeredComponent;
		}
		return $return;
	}
	
	/**
	 * Returns the value of a variable (or null if the variable is not set).
	 * Variables can contain anything, and are used by some modules for different
	 * purposes. For instance, the list of repositories is stored as a variables, etc...
	 * 
	 * @param string $name
	 */
	public function getVariable($name) {
		if (isset($this->variables[$name])) {
			return $this->variables[$name];
		} else {
			return null;
		}
	}

	/**
	 * Returns whether the variable is set or not.
	 * 
	 * @param string $name
	 */
	public function issetVariable($name) {
		return isset($this->variables[$name]);
	}
	
	
	/**
	 * Sets the value of a variable.
	 * Variables can contain anything, and are used by some modules for different
	 * purposes. For instance, the list of repositories is stored as a variables, etc...
	 * 
	 * @param string $name
	 */
	public function setVariable($name, $value) {
		$this->variables[$name] = $value;
	}
	
	/**
	 * Sets all the variables, at once.
	 * Used at load time to initialize all variables. 
	 * 
	 * @param array $variables
	 */
	public function setAllVariables(array $variables) {
		$this->variables = $variables;
	}
	
	/**
	 * Internal use only. Sets all the classes that can be autoloaded
	 * 
	 * @param array<className, fileName> $classes
	 */
	public function registerAutoloadedClasses($classes) {
		$this->autoloadableClasses = $classes;
	}
	
	/**
	 * Autoload a class not load
	 * 
	 * @param string $className
	 */
	public function autoload($className) {
		if(isset($this->autoloadableClasses[$className]))
			require_once ROOT_PATH.$this->autoloadableClasses[$className];
	}
	
	/**
	 * Internal use only. Force loading all classes (even the one that can be autoloaded)
	 * 
	 */
	public function forceAutoload() {
		if($this->autoloadableClasses) {
			foreach ($this->autoloadableClasses as $class => $file) {
				require_once ROOT_PATH.$file;
			}
		}
	}
	
	/**
	 * Returns the scope for this MoufManager.
	 * The scope can be one of MoufManager::SCOPE_APP (the main application) or MoufManager::SCOPE_ADMIN (the Mouf instance for the admin)
	 * 
	 * @return string 
	 */
	public function getScope() {
		return $this->scope;
	}
	
	/**
	 * This function will delete any weak instance that would not be referred anymore.
	 * This is used to garbage-collect any unused weak instances.
	 */
	private function purgeUnreachableWeakInstances() {
		foreach ($this->declaredInstances as $key=>$instance) {
			if (!isset($instance['weak']) || $instance['weak'] == false) {
				$this->walkForGarbageCollection($this->declaredInstances[$key]);
			}
		}
		
		// At this point any instance with the "noGarbageCollect" attribute should be kept. Others should be eliminated.
		$keptInstances = array();
		foreach ($this->declaredInstances as $key=>$instance) {
			if (isset($instance['noGarbageCollect']) && $instance['noGarbageCollect'] == true) {
				// Let's clear the flag
				unset($this->declaredInstances[$key]['noGarbageCollect']);
			} else {
				// Let's delete the weak instance
				unset($this->declaredInstances[$key]);
			}
		}
		
		
	}
	
	/**
	 * Recursive function that mark this instance as NOT garbage collectable and go through referred nodes.
	 * 
	 * @param array $instance
	 */
	private function walkForGarbageCollection(&$instance) {
		if (isset($instance['noGarbageCollect']) && $instance['noGarbageCollect'] == true) {
			// No need to go through already visited nodes.
			return;
		}
		
		$instance['noGarbageCollect'] = true;
		
		if (isset($instance['fieldBinds'])) {
			foreach ($instance['fieldBinds'] as $prop) {
				if(is_array($prop)) {
					foreach ($prop as $singleProp) {
						$this->walkForGarbageCollection($this->declaredInstances[$singleProp]);
					}
				}
				else {
					$this->walkForGarbageCollection($this->declaredInstances[$prop]);
				}
			}
		}
		if (isset($instance['setterBinds'])) {
			foreach ($instance['setterBinds'] as $prop) {
				if(is_array($prop)) {
					foreach ($prop as $singleProp) {
						$this->walkForGarbageCollection($this->declaredInstances[$singleProp]);
					}
				}
				else {
					$this->walkForGarbageCollection($this->declaredInstances[$prop]);
				}
			}
		}
	}
	
	/**
	 * Returns true if the instance is week
	 * 
	 * @param string $instanceName
	 * @return bool
	 */
	public function isInstanceWeak($instanceName) {
		if (isset($this->declaredInstances[$instanceName]['weak'])) {
			return $this->declaredInstances[$instanceName]['weak'];
		} else {
			return false;
		}
	}
	
	/**
	 * Decides whether an instance should be weak or not.
	 * @param string $instanceName
	 * @param bool $weak
	 */
	public function setInstanceWeakness($instanceName, $weak) {
		$this->declaredInstances[$instanceName]['weak'] = $weak;
	}
	
	
	/**
	 * Returns true if the instance is anonymous
	 *
	 * @param string $instanceName
	 * @return bool
	 */
	public function isInstanceAnonymous($instanceName) {
		if (isset($this->declaredInstances[$instanceName]['anonymous'])) {
			return $this->declaredInstances[$instanceName]['anonymous'];
		} else {
			return false;
		}
	}
	
	/**
	 * Decides whether an instance is anonymous or not.
	 * @param string $instanceName
	 * @param bool $anonymous
	 */
	public function setInstanceAnonymousness($instanceName, $anonymous) {
		if ($anonymous) {
			$this->declaredInstances[$instanceName]['anonymous'] = true;
			// An anonymous object must be weak.
			$this->declaredInstances[$instanceName]['weak'] = true;
		} else {
			unset($this->declaredInstances[$instanceName]['anonymous']);
		}
	}
	
	/**
	 * Returns an "anonymous" name for an instance.
	 * "anonymous" names start with "__anonymous__" and is followed by a number.
	 * This function will return a name that is not already used.
	 * 
	 * @return string
	 */
	public function getFreeAnonymousName() {
		
		$i=0;
		do {
			$anonName = "__anonymous__".$i;
			if (!isset($this->declaredInstances[$anonName])) {
				break;
			}
			$i++;
		} while (true);
		
		return $anonName;
	}
	
	/**
	 * An array of instanciated MoufInstanceDescriptor objects.
	 * These descriptors are created by getInstanceDescriptor or createInstance function.
	 * 
	 * @var array<string, MoufInstanceDescriptor>
	 */
	private $instanceDescriptors;
	
	/**
	 * Returns an object describing the instance whose name is $name.
	 * 
	 * @param string $name
	 * @return MoufInstanceDescriptor
	 */
	public function getInstanceDescriptor($name) {
		if (isset($this->instanceDescriptors[$name])) {
			return $this->instanceDescriptors[$name];
		} elseif (isset($this->declaredInstances[$name])) {
			return new MoufInstanceDescriptor($this, $name);
		} else {
			throw new MoufException("Instance '".$name."' does not exist.");
		}
	}
	
	/**
	 * Creates a new instance and returns the instance descriptor.
	 * @param string $className The name of the class of the instance.
	 * @param int $mode Depending on the mode, the behaviour will be different if an instance with the same name already exists.
	 * @return MoufInstanceDescriptor
	 */
	public function createInstance($className, $mode = self::DECLARE_ON_EXIST_EXCEPTION) {
		$name = $this->getFreeAnonymousName();
		$this->declareComponent($name, $className, false, $mode);
		$this->setInstanceAnonymousness($name, true);
		return $this->getInstanceDescriptor($name);
	}
	
	/**
	 * A list of descriptors.
	 * 
	 * @var array<string, MoufXmlReflectionClass>
	 */
	private $classDescriptors = array();
	
	/**
	 * Returns an object describing the class passed in parameter.
	 * The class must be included by Mouf (using the "include PHP files" features, or be part of an enabled package)
	 * This method should only be called in the context of the Mouf administration UI.
	 *   
	 * @param string $className The name of the class to import
	 * @return MoufXmlReflectionClass
	 */
	public function getClassDescriptor($className) {
		if (!isset($this->classDescriptors[$className])) {
			// FIXME: il faudrait optimiser pour faire en sorte qu'on puisse appeler cette métode même hors du contexte de Mouf administration UI.
			// Pour cela, il faudrait savoir si quel class-loader est actif!!!!
			if (MoufManager::getMoufManager()->getScope() == self::SCOPE_APP && $this->getScope() == self::SCOPE_APP) {
				// We are fully in the scope of the application:
				
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOO: J'AI CASSE UN TRUC ICI!!!!!
				$this->classDescriptors[$className] = new MoufReflectionClass($className);
			} else {
				$this->classDescriptors[$className] = MoufReflectionProxy::getClass($className, $this->getScope() == self::SCOPE_ADMIN);
			}
		}
		return $this->classDescriptors[$className];
	}
}
?>