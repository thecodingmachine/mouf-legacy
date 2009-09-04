<?php

require_once 'MoufPackage.php';

class MoufPackageManager {
	
	/**
	 * The plugins directory path.
	 *
	 * @var string
	 */
	private $pluginsDir;
	
	/**
	 * The list of packages that has been loaded so far.
	 * The key is the path to the packacges.xml file relative to the "plugins" directory.
	 *
	 * @var array<string, MoufPackage>
	 */
	private $packages = array();
	
	/**
	 * Enter description here...
	 *
	 * @param string $pluginsDir The plugins directory path. Defaults to "../plugins"
	 */
	public function __construct($pluginsDir = "../plugins") {
		$this->pluginsDir = $pluginsDir;
	}
	
	/**
	 * Returns the list of packages that have been found in the "plugins" directory.
	 *
	 * @return array<MoufPackage>
	 */
	public function getPackagesList() {
		$currentDir = getcwd();
		
		//var_dump(glob("*", GLOB_ONLYDIR));
		
		chdir($this->pluginsDir);
		$packages = $this->scanDir(".", array());
		chdir($currentDir);
		
		return $packages;
	}
	
	/**
	 * Scans a directory. If the directory contains a package.xml file, the MoufPackage object is created,
	 * and subdirectories are not searched. Otherwise, subdirectories are searched for a
	 * package.xml file.
	 * 
	 * @param string $currentDir
	 * @param array<MoufPackage> $packageList
	 * @return array<MoufPackage>
	 */
	private function scanDir($currentDir, $packageList) {
		
		
		if (file_exists("$currentDir/package.xml")) {
			$package = $this->getPackage($currentDir."/package.xml");
			$packageList[] = $package;
			return $packageList;
		}
		
		$directories = glob("$currentDir/*", GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			//echo "scanning ".$directory."<br/>";
			$packageList = $this->scanDir($directory, $packageList); 
		}
		return $packageList;
	}
	
	/**
	 * Creates an instance of the MoufPackage object from the package.xml file.
	 *
	 * @param string $fileName
	 * @return MoufPackage
	 */
	private function createPackage($fileName) {
		$package = new MoufPackage();
		
		$currentDir = getcwd();
		chdir($this->pluginsDir);
		$package->initFromFile($fileName);
		chdir($currentDir);
		
		$this->packages[$fileName] = $package;
		return $package;
	}
	
	/**
	 * Returns the MoufPackage object from the package.xml file (relative to the "plugins" directory).
	 *
	 * @param string $fileName
	 * @return MoufPackage
	 */
	public function getPackage($fileName) {
		if (isset($this->packages[$fileName])) {
			return $this->packages[$fileName];
		} else {
			return $this->createPackage($fileName);
		}
	}
	
	/**
	 * Returns the list of depedencies (recursively) for this package.
	 * Dependencies are ordered.
	 *
	 * @param MoufPackage $package
	 * @return array<MoufPackage>
	 */
	public function getDependencies(MoufPackage $package) {
		return $this->getRecursiveDependencies($package, array());
	}
	
	/**
	 * Recurse through the dependencies.
	 *
	 * @param MoufPackage $package
	 * @param array<MoufPackage> $packageDependencies
	 */
	private function getRecursiveDependencies(MoufPackage $package, array $packageDependencies) {
		$descriptors = $package->getDependenciesAsDescriptors();
		foreach ($descriptors as $descriptor) {
			$fileName = $descriptor->getPackageXmlPath();
			$additionalPackage = $this->getPackage($fileName);
			if (array_search($additionalPackage, $packageDependencies)) {
				continue;
			}
			
			$packageDependencies[] = $additionalPackage;
			$packageDependencies = $this->getRecursiveDependencies($additionalPackage, $packageDependencies);
		}
		return $packageDependencies;
	}
}
?>