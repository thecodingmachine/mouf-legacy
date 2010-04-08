<?php

require_once 'MoufException.php';
require_once 'MoufInstanceNotFoundException.php';

/**
 * The class managing object instanciation in the Mouf framework.
 * Users should use the "Mouf" class instead.
 *
 */
class MoufManager {
	
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
			self::$defaultInstance->componentsFileName = "../MoufComponents.php";
			self::$defaultInstance->requireFileName = "../MoufRequire.php";
			self::$defaultInstance->adminUiFileName = "../MoufUI.php";
			self::$defaultInstance->mainClassName = "Mouf";
			self::$defaultInstance->pathToMouf = "mouf/";
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
		self::$defaultInstance->componentsFileName = "MoufAdminComponents.php";
		self::$defaultInstance->requireFileName = "MoufAdminRequire.php";
		self::$defaultInstance->adminUiFileName = "MoufAdminUI.php";
		self::$defaultInstance->mainClassName = "MoufAdmin";
		self::$defaultInstance->pathToMouf = "";
	}
	
	/**
	 * The array of components managed by mouf
	 *
	 * @var array
	 */
	private $objectInstances = array();
	
	/**
	 * The array of components that might be created by mouf.
	 *
	 * @var array An array associting the instance name to the name of the class to be instanciated.
	 */
	private $declaredComponents = array();
	
	/**
	 * An array binding the components to their properties
	 *
	 * @var array<array<string>>
	 */
	private $declaredProperties = array();
	
	/**
	 * An array binding the components to their properties
	 *
	 * @var array
	 */
	private $declaredSetterProperties = array();
	
	/**
	 * An array binding the components to their properties related to other components
	 *
	 * @var array<string, array<string, string>> ou array<string, array<string, array<string>>> si il y a plusieurs composants.
	 */
	private $declaredBinds = array();
	
	/**
	 * An array binding the components to their properties related to other components, using a setter
	 *
	 * @var array<string, array<string, string>> ou array<string, array<string, array<string>>> si il y a plusieurs composants.
	 */
	private $declaredSetterBinds = array();
	
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
	 * Returns the list of all instances of objects in Mouf.
	 * Objects are not instanciated. Instead, a list containing the name of the instance in the key
	 * and the name of the class in the value is returned.
	 *
	 * @return array<string, string>
	 */
	public function getInstancesList() {
		return $this->declaredComponents;
	}
	
	/**
	 * Declares a new component.
	 *
	 * @param string $instanceName
	 * @param string $className
	 * @param boolean $external Whether the component is external or not. Defaults to false.
	 */
	public function declareComponent($instanceName, $className, $external = false) {
		$this->declaredComponents[$instanceName] = $className;
		if ($external) {
			$this->externalComponents[$instanceName] = true;
		}
	}
	
	/**
	 * Removes an instance.
	 * Sets to null any property linking to that component.
	 *
	 * @param string $instanceName
	 */
	public function removeComponent($instanceName) {
		unset($this->declaredComponents[$instanceName]);
		unset($this->objectInstances[$instanceName]);
		unset($this->declaredProperties[$instanceName]);
		unset($this->declaredSetterProperties[$instanceName]);
		unset($this->declaredBinds[$instanceName]);
		unset($this->declaredSetterBinds[$instanceName]);
		unset($this->externalComponents[$instanceName]);
		
		if (is_array($this->declaredBinds)) {
			foreach ($this->declaredBinds as $instanceName=>$bindedProperties) {
				if (is_array($bindedProperties)) {
					foreach ($bindedProperties as $paramName=>$properties) {
						if (is_array($properties)) {
							// If this is an array of properties
							$keys_matching = array_keys($properties, $instanceName);
							if (!empty($keys_matching)) {
								foreach ($keys_matching as $key) {
									unset($properties[$key]); 
								}
								$this->bindComponents($instanceName, $paramName, $properties);
							}
						} else {
							// If this is a simple property
							if ($properties == $instanceName) {
								$this->bindComponent($instanceName, $paramName, null);
							}
						}
					}
				}
			}
		}
		
		if (is_array($this->declaredSetterBinds)) {
			foreach ($this->declaredSetterBinds as $instanceName=>$bindedProperties) {
				if (is_array($bindedProperties)) {
					foreach ($bindedProperties as $setterName=>$properties) {
						if (is_array($properties)) {
							// If this is an array of properties
							$keys_matching = array_keys($properties, $instanceName);
							if (!empty($keys_matching)) {
								foreach ($keys_matching as $key) {
									unset($properties[$key]); 
								}
								$this->bindComponentsViaSetter($instanceName, $setterName, $properties);
							}
						} else {
							// If this is a simple property
							if ($properties == $instanceName) {
								$this->bindComponentViaSetter($instanceName, $setterName, null);
							}
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
		if (isset($this->declaredComponents[$newInstanceName])) {
			throw new MoufException("Impossible to rename instance $instanceName to $newInstanceName: This instance already exists.");
		}
		
		$this->declaredComponents[$newInstanceName] = $this->declaredComponents[$instanceName];
		unset($this->declaredComponents[$instanceName]);
		
		if (isset($this->objectInstances[$instanceName])) {
			$this->objectInstances[$newInstanceName] = $this->objectInstances[$instanceName];
			unset($this->objectInstances[$instanceName]);
		}
		if (isset($this->declaredProperties[$instanceName])) {
			$this->declaredProperties[$newInstanceName] = $this->declaredProperties[$instanceName];
			unset($this->declaredProperties[$instanceName]);
		}
		if (isset($this->declaredSetterProperties[$instanceName])) {
			$this->declaredSetterProperties[$newInstanceName] = $this->declaredSetterProperties[$instanceName];
			unset($this->declaredSetterProperties[$instanceName]);
		}
		if (isset($this->declaredBinds[$instanceName])) {
			$this->declaredBinds[$newInstanceName] = $this->declaredBinds[$instanceName];
			unset($this->declaredBinds[$instanceName]);
		}
		if (isset($this->declaredSetterBinds[$instanceName])) {
			$this->declaredSetterBinds[$newInstanceName] = $this->declaredSetterBinds[$instanceName];
			unset($this->declaredSetterBinds[$instanceName]);
		}
		
		if (is_array($this->declaredBinds)) {
			foreach ($this->declaredBinds as $compInstanceName=>$bindedProperties) {
				if (is_array($bindedProperties)) {
					foreach ($bindedProperties as $paramName=>$properties) {
						if (is_array($properties)) {
							// If this is an array of properties
							$keys_matching = array_keys($properties, $instanceName);
							
							if (!empty($keys_matching)) {
								foreach ($keys_matching as $key) {
									$properties[$key] = $newInstanceName; 
								}
								$this->bindComponents($compInstanceName, $paramName, $properties);
							}
						} else {
							// If this is a simple property
							if ($properties == $instanceName) {
								$this->bindComponent($compInstanceName, $paramName, $newInstanceName);
							}
						}
					}
				}
			}
		}
		
		if (is_array($this->declaredSetterBinds)) {
			foreach ($this->declaredSetterBinds as $compInstanceName=>$bindedProperties) {
				if (is_array($bindedProperties)) {
					foreach ($bindedProperties as $setterName=>$properties) {
						if (is_array($properties)) {
							// If this is an array of properties
							$keys_matching = array_keys($properties, $instanceName);
							if (!empty($keys_matching)) {
								foreach ($keys_matching as $key) {
									$properties[$key] = $newInstanceName; 
								}
								$this->bindComponentsViaSetter($compInstanceName, $setterName, $properties);
							}
						} else {
							// If this is a simple property
							if ($properties == $instanceName) {
								$this->bindComponentsViaSetter($compInstanceName, $setterName, $newInstanceName);
							}
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
		return $this->declaredComponents[$instanceName];
	}
	
	/**
	 * Instantiate the object (and any object needed on the way)
	 *
	 */
	private function instantiateComponent($instanceName) {
		if (isset($this->declaredComponents[$instanceName])) {
			$className = $this->declaredComponents[$instanceName];
		} else {
			throw new MoufInstanceNotFoundException("The object instance ".$instanceName." is not defined.", $instanceName);
		}
		
		/*if (!class_exists($className)) {
			throw new Exception("Unable to find the class to instantiate");
		}*/
		
		$object = new $className();
		$this->objectInstances[$instanceName] = $object;
		if (isset($this->declaredProperties[$instanceName]) && is_array($this->declaredProperties[$instanceName])) {
			foreach ($this->declaredProperties[$instanceName] as $key=>$value) {
				$object->$key = $value;
			}
		}
		
		if (isset($this->declaredSetterProperties[$instanceName]) && is_array($this->declaredSetterProperties[$instanceName])) {
			foreach ($this->declaredSetterProperties[$instanceName] as $key=>$value) {
				$object->$key($value);
			}
		}
		
		if (isset($this->declaredBinds[$instanceName]) && is_array($this->declaredBinds[$instanceName])) {
			foreach ($this->declaredBinds[$instanceName] as $key=>$value) {
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
		
		if (isset($this->declaredSetterBinds[$instanceName]) && is_array($this->declaredSetterBinds[$instanceName])) {
			foreach ($this->declaredSetterBinds[$instanceName] as $key=>$value) {
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
	 */
	public function setParameter($instanceName, $paramName, $paramValue) {
		$this->declaredProperties[$instanceName][$paramName] = $paramValue;
	}
	
	/**
	 * Binds a parameter to the instance using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @param string $paramValue
	 */
	public function setParameterViaSetter($instanceName, $setterName, $paramValue) {
		$this->declaredSetterProperties[$instanceName][$setterName] = $paramValue;
	}
	
	/**
	 * Unsets all the parameters (using a property or a setter) for the given instance.
	 *
	 * @param string $instanceName The instance to consider
	 */
	public function unsetAllParameters($instanceName) {
		unset($this->declaredProperties[$instanceName]);
		unset($this->declaredSetterProperties[$instanceName]);
	}
	
	/**
	 * Returns the value for the given parameter.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @return mixed
	 */
	public function getParameter($instanceName, $paramName) {
		return $this->declaredProperties[$instanceName][$paramName];
	}
	
	/**
	 * Returns the value for the given parameter that has been set using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @return mixed
	 */
	public function getParameterForSetter($instanceName, $setterName) {
		return $this->declaredSetterProperties[$instanceName][$setterName];
	}
	
	/**
	 * Returns true if the param is set for the given instance.
	 *
	 * @param string $instanceName
	 * @param string $paramName
	 * @return boolean
	 */
	public function hasParameter($instanceName, $paramName) {
		return isset($this->declaredProperties[$instanceName][$paramName]);
	}
	
	/**
	 * Returns true if the param is set for the given instance using a setter.
	 *
	 * @param string $instanceName
	 * @param string $setterName
	 * @return boolean
	 */
	public function hasParameterForSetter($instanceName, $setterName) {
		return isset($this->declaredSetterProperties[$instanceName][$setterName]);
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
			unset($this->declaredBinds[$instanceName][$paramName]);
		} else {
			$this->declaredBinds[$instanceName][$paramName] = $paramValue;
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
			unset($this->declaredSetterBinds[$instanceName][$setterName]);
		} else {
			$this->declaredSetterBinds[$instanceName][$setterName] = $paramValue;
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
			unset($this->declaredBinds[$instanceName][$paramName]);
		} else {
			$this->declaredBinds[$instanceName][$paramName] = $paramValue;
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
			unset($this->declaredSetterBinds[$instanceName][$setterName]);
		} else {
			$this->declaredSetterBinds[$instanceName][$setterName] = $paramValue;
		}
	}	

	/**
	 * This simply adds the passed file to the list of "registered components".
	 * The list will be required by mouf.php when it is generated using "rewriteMouf" function.
	 *
	 * @param unknown_type $fileName
	 */
	public function registerComponent($fileName) {
		$this->registeredComponents[] = $fileName;
	}
	
	/**
	 * This function will rewrite the Mouf.php file according to parameters stored in the MoufManager
	 * TODO: protect special characters!!!!
	 */
	public function rewriteMouf() {
		$fp = fopen(dirname(__FILE__)."/".$this->componentsFileName, "w");
		fwrite($fp, "<?php\n");
		fwrite($fp, "/**\n");
		fwrite($fp, " * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.\n");
		fwrite($fp, " */\n");
		fwrite($fp, "require_once '".$this->pathToMouf."MoufManager.php';\n");
		fwrite($fp, "MoufManager::initMoufManager();\n");
		fwrite($fp, "\$moufManager = MoufManager::getMoufManager();\n");
		fwrite($fp, "\n");
		
		// Declare packages
		foreach ($this->packagesList as $fileName) {
			fwrite($fp, "\$moufManager->addPackageByXmlFile(".var_export($fileName, true).");\n");
		}
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
		
		// Declare local components
		foreach ($this->registeredComponents as $registeredComponent) {
			//fwrite($fp, "require_once dirname(__FILE__).'/$registeredComponent';\n");
			fwrite($fp, "\$moufManager->registerComponent('$registeredComponent');\n");
		}
		fwrite($fp, "\n");
		
		foreach ($this->declaredComponents as $name=>$className) {
			if (!isset($this->externalComponents[$name]) || $this->externalComponents[$name] != true) {
				fwrite($fp, "\$moufManager->declareComponent(".var_export($name, true).", ".var_export($className, true).");\n");
			}
		}
		fwrite($fp, "\n");

		foreach ($this->declaredProperties as $instanceName=>$propArray) {
			if (!isset($this->externalComponents[$instanceName]) || $this->externalComponents[$instanceName] != true) {
				if (is_array($propArray)) {
					foreach ($propArray as $propName=>$propValue) {
						fwrite($fp, "\$moufManager->setParameter(".var_export($instanceName, true).", ".var_export($propName, true).", ".var_export($propValue, true).");\n");
					}
				}
			}
		}
		fwrite($fp, "\n");
		
		foreach ($this->declaredSetterProperties as $instanceName=>$propArray) {
			if (!isset($this->externalComponents[$instanceName]) || $this->externalComponents[$instanceName] != true) {
				if (is_array($propArray)) {
					foreach ($propArray as $propName=>$propValue) {
						fwrite($fp, "\$moufManager->setParameterViaSetter(".var_export($instanceName, true).", ".var_export($propName, true).", ".var_export($propValue, true).");\n");
					}
				}
			}
		}
		fwrite($fp, "\n");
		
		foreach ($this->declaredBinds as $instanceName=>$propArray) {
			if (!isset($this->externalComponents[$instanceName]) || $this->externalComponents[$instanceName] != true) {
				if (is_array($propArray)) {
					foreach ($propArray as $propName=>$propValue) {
						if (is_array($propValue)) {
							fwrite($fp, "\$moufManager->bindComponent(".var_export($instanceName, true).", ".var_export($propName, true).", ".var_export($propValue, true).");\n");
						} else {
							fwrite($fp, "\$moufManager->bindComponents(".var_export($instanceName, true).", ".var_export($propName, true).", ".var_export($propValue, true).");\n");
						}
					}
				}
			}
		}
		fwrite($fp, "\n");
		
		foreach ($this->declaredSetterBinds as $instanceName=>$propArray) {
			if (!isset($this->externalComponents[$instanceName]) || $this->externalComponents[$instanceName] != true) {
				if (is_array($propArray)) {
					foreach ($propArray as $propName=>$propValue) {
						if (is_array($propValue)) {
							fwrite($fp, "\$moufManager->bindComponentViaSetter(".var_export($instanceName, true).", ".var_export($propName, true).", ".var_export($propValue, true).");\n");
						} else {
							fwrite($fp, "\$moufManager->bindComponentsViaSetter(".var_export($instanceName, true).", ".var_export($propName, true).", ".var_export($propValue, true).");\n");
						}
					}
				}
			}
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
		
		foreach ($this->declaredComponents as $name=>$className) {
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
		
		// Now, let's write the MoufRequire file that contains all the "require_once" stuff.
		$fp2 = fopen(dirname(__FILE__)."/".$this->requireFileName, "w");
		fwrite($fp2, "<?php\n");
		fwrite($fp2, "/**\n");
		fwrite($fp2, " * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.\n");
		fwrite($fp2, " */\n");
		fwrite($fp2, "\n");

		// Let's add the require_once from the packages first!
		fwrite($fp2, "// Packages dependencies\n");
		fwrite($fp2, "\$localFilePath = dirname(__FILE__);\n");
		foreach ($this->packagesList as $fileName) {
			$package = $packageManager->getPackage($fileName);
			foreach ($package->getRequiredFiles() as $requiredFile) {
				fwrite($fp2, "require_once \$localFilePath.'/".$this->pathToMouf."../plugins/".$package->getPackageDirectory()."/".$requiredFile."';\n");
			}
		}
		fwrite($fp2, "\n");
		
		fwrite($fp2, "// User dependencies\n");
		
		foreach ($this->registeredComponents as $registeredComponent) {
			fwrite($fp2, "require_once \$localFilePath.'/$registeredComponent';\n");
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
		foreach ($this->declaredComponents as $instanceName=>$className) {
			$obj = new $className();
			if (is_a($obj, $instanceType)) {
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
		if (isset($this->declaredBinds[$instanceName]) && isset($this->declaredBinds[$instanceName][$propertyName]))
			return $this->declaredBinds[$instanceName][$propertyName];
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
		if (isset($this->declaredSetterBinds[$instanceName]) && isset($this->declaredSetterBinds[$instanceName][$setterName]))
			return $this->declaredSetterBinds[$instanceName][$setterName];
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
		//error_log("fdsf".$instanceName);
		//error_log("toto ".var_export($this->declaredBinds, true));
		$binds = array();
		if (isset($this->declaredBinds[$instanceName])) {
			$binds = $this->declaredBinds[$instanceName];
		}
		if (isset($this->declaredSetterBinds[$instanceName])) {
			foreach ($this->declaredSetterBinds[$instanceName] as $setter=>$bind) {
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
		
		foreach ($this->declaredBinds as $scannedInstance=>$declaredBind) {
			foreach ($declaredBind as $declaredBindProperty) {
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
		foreach ($this->declaredSetterBinds as $scannedInstance=>$declaredBind) {
			foreach ($declaredBind as $declaredBindProperty) {
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
		foreach ($this->registeredComponents as $file) {
			$realpathFile = realpath($dirMoufFile."/".$file);
			$relativeFile = substr($realpathFile, strlen($fulldir)+1);
			$relativeFile = str_replace("\\", "/", $relativeFile);
			$fileArray[] = $relativeFile;
		}
		
		return $fileArray;
	}
	
	/**
	 * Sets a list of files that will be included by Mouf, relative to the root of the project.
	 *
	 * @param array<string> $files
	 */
	public function setRegisteredComponentFiles($files) {
		
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
			$registeredComponentsFile[] = $this->createRelativePath($dirMoufFile, $fileFull);
		}
		
		$this->registeredComponents = $registeredComponentsFile;
	}

	/**
	 * Adds one file that will be included by Mouf, relative to the root of the project.
	 * 
	 * @param string $file
	 */
	public function addRegisteredComponentFile($file) {
		$dirMoufFile = dirname(dirname(__FILE__)."/".$this->requireFileName);
		$fulldir = realpath(dirname(__FILE__)."/../");
		$fulldir = str_replace("\\", "/", $fulldir);
		// Depending on the version of PHP, we might or might not have a trailing /. Let's add it.
		if (substr($fulldir, -1) != "/" ) {
			$fulldir .= "/";
		}

		$fileFull = $fulldir.$file;
		if (array_search($file, $this->registeredComponents) === false) {
			$this->registeredComponents[] = $this->createRelativePath($dirMoufFile, $fileFull);
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
		
		
		/*
		
		for ($i=0; $i<count($realPathFromDirArray); $i++) {
			// Let's stop when there is nothing in common anymore.
			if ($realPathFromDirArray[$i] != $realPathToFileArray[$i]) {
				break; 
			}
		}*/
		$nbDirsRemaining = count($realPathFromDirArray);
		
		
		$path = str_repeat("../", $nbDirsRemaining).implode("/", $realPathToFileArray);
		return $path;
	}
	
	/**
	 * Adds a package by providing the path to the package.xml file.
	 * The path is relative to the "plugins" directory. 
	 *
	 * @param string $fileName
	 */
	public function addPackageByXmlFile($fileName) {
		$this->packagesList[] = $fileName;
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
		return dirname(__FILE__)."/../".$this->pathToMouf."../plugins";
	}
	
	/**
	 * Duplicates an instance.
	 *
	 * @param string $srcInstanceName The name of the source instance.
	 * @param string $destInstanceName The name of the new instance.
	 */
	public function duplicateInstance($srcInstanceName, $destInstanceName) {
		if (!isset($this->declaredComponents[$srcInstanceName])) {
			throw new MoufException("Error while duplicating instance: unable to find source instance ".$srcInstanceName);
		}
		if (isset($this->declaredComponents[$destInstanceName])) {
			throw new MoufException("Error while duplicating instance: the dest instance already exists: ".$destInstanceName);
		}
		$this->declaredComponents[$destInstanceName] = $this->declaredComponents[$srcInstanceName];
		
		if (isset($this->declaredProperties[$srcInstanceName]))
			$this->declaredProperties[$destInstanceName] = $this->declaredProperties[$srcInstanceName];
		if (isset($this->declaredSetterProperties[$srcInstanceName]))
			$this->declaredSetterProperties[$destInstanceName] = $this->declaredSetterProperties[$srcInstanceName];
		if (isset($this->declaredBinds[$srcInstanceName]))
			$this->declaredBinds[$destInstanceName] = $this->declaredBinds[$srcInstanceName];
		if (isset($this->declaredSetterBinds[$srcInstanceName]))
			$this->declaredSetterBinds[$destInstanceName] = $this->declaredSetterBinds[$srcInstanceName];
	}
	
}
?>