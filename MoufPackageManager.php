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
			$package = $this->createPackage($currentDir."/package.xml");
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
	 * Returns a MoufPackage object from the package.xml file.
	 *
	 * @param string $fileName
	 * @return MoufPackage
	 */
	private function createPackage($fileName) {
		$package = new MoufPackage();
		$package->initFromFile($fileName);
		return $package;
	}
}
?>