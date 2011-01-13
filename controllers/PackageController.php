<?php
require_once dirname(__FILE__)."/../MoufPackageManager.php";

/**
 * The controller managing the list of packages included in the Mouf plugins directory.
 *
 * @Component
 */
class PackageController extends Controller implements DisplayPackageListInterface {

	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * The package to enable/disable.
	 *
	 * @var MoufPackage
	 */
	public $package;
	
	/**
	 * The list of packages found on the system.
	 *
	 * @var array<MoufPackage>
	 */
	public $moufPackageList;
	
	/**
	 * The root of the hierarchy of packages
	 * 
	 * @var MoufGroupDescriptor
	 */
	public $moufPackageRoot;
	
	
	/**
	 * The list of dependencies to the selected package.
	 *
	 * @var array<MoufPackage>
	 */
	public $moufDependencies;
	
	public $validationMsg;
	public $validationPackageList;
	
	
	/**
	 * A list of all instances to delete with the package.
	 * 
	 *
	 * @var array<instancename, classname>
	 */
	public $toDeleteInstance;
	
	/**
	 * Displays the list of component files
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 * @param string $validation The validation message to display (either null, or enable or disable).
	 * @param array<string> $packageList The array of packages enabled or disabled.
	 */
	public function defaultAction($selfedit = "false", $validation = null, $packageList = null) {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->validationMsg = $validation;
		$this->validationPackageList = $packageList;
				
		$packageManager = new MoufPackageManager();
		
		$this->moufPackageRoot = $packageManager->getOrderedPackagesList();
		
		$this->moufPackageList = $packageManager->getPackagesList();
		// Packages are almost sorted correctly.
		// However, we should make a bit of sorting to transform this:
		// javascript/jit
		// javascript/jquery/jquery
		// javascript/prototype
		// into this:
		// javascript/jit
		// javascript/prototype
		// javascript/jquery/jquery
		// (directories at the end)
		// Furthermore, we will sort packages with different version numbers by version number.
		// So we will sort by group, then package, then version:
		uasort($this->moufPackageList, array($this, "comparePackageGroup"));
		$this->template->addContentFile("views/packages/displayPackagesList.php", $this);
		$this->template->draw();	
	}
	
	public function comparePackageGroup(MoufPackage $package1, MoufPackage $package2) {
		$group1 = $package1->getDescriptor()->getGroup();
		$group2 = $package2->getDescriptor()->getGroup();
		$cmp = strcmp($group1, $group2);
		if ($cmp == 0) {
			$nameCmp = strcmp($package1->getDescriptor()->getName(), $package2->getDescriptor()->getName());
			if ($nameCmp != 0) {
				return $nameCmp;
			} else {
				return -MoufPackageDescriptor::compareVersionNumber($package1->getDescriptor()->getVersion(), $package2->getDescriptor()->getVersion());
			} 
		} else 
			return $cmp;
	}
	
	/**
	 * Action that is run to enable a package.
	 *
	 * @Action
	 * @Logged
	 * @param string $name The path to the package.xml file relative to the plugins directory.
	 * @param string $selfedit
	 * @param string $confirm
	 */
	public function enablePackage($name, $selfedit = "false", $confirm="false") {
		// First, let's find the list of depending packages.
		$this->selfedit = $selfedit;
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
	
		$packageManager = new MoufPackageManager();
		$this->package = $packageManager->getPackage($name);
		//$this->moufDependencies = $packageManager->getDependencies($this->package);
		$this->moufDependencies = $packageManager->getDependencies($this->package, $this->moufManager);
		//var_dump($this->moufDependencies); exit;
				
		if (!empty($this->moufDependencies) && $confirm=="false") {
			$this->template->addContentFile("views/packages/displayConfirmPackagesEnable.php", $this);
			$this->template->draw();
		} else {
			
			if (!array_search($this->package, $this->moufDependencies)) {
				$this->moufDependencies[] = $this->package;
			}
			
			foreach ($this->moufDependencies as $dependency) {
				$this->moufManager->addPackageByXmlFile($dependency->getDescriptor()->getPackageXmlPath());
			}
			$this->moufManager->rewriteMouf();
			
			$url = "Location: ".ROOT_URL."mouf/packages/?selfedit=".$selfedit."&validation=enable";
			foreach ($this->moufDependencies as $moufDependency) {
				$url.= "&packageList[]=".$moufDependency->getDescriptor()->getPackageDirectory();
			}
			header($url);	
		}
	}
	
	/**
	 * Action that is run to disable a package.
	 *
	 * @Action
	 * @Logged
	 * @param string $name The path to the package.xml file relative to the plugins directory.
	 * @param string $selfedit
	 * @param string $confirm
	 */
	public function disablePackage($name, $selfedit = "false", $confirm="false") {
		// First, let's find the list of depending packages.
		$this->selfedit = $selfedit;
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
	
		$packageManager = new MoufPackageManager();
		$this->package = $packageManager->getPackage($name);
		//$this->moufDependencies = $packageManager->getDependencies($this->package);
		
		$this->moufDependencies = $packageManager->getInstalledPackagesUsingThisPackage($this->package, $this->moufManager);
		
		//$dependencies = $packageManager->getChildren($this->package);
		//$this->moufDependencies = array();
		// Let's only keep the packages that are already installed from this list:
		/*foreach ($dependencies as $moufDependency) {
			$enabled = $this->moufManager->isPackageEnabled($moufDependency->getDescriptor()->getPackageXmlPath());
			if ($enabled) {
				$this->moufDependencies[] = $moufDependency;
			}
		}*/
		
		// Let's add the package to be removed to the list of package.
		$this->moufDependencies[] = $this->package;
		
		// Now, let's find the list of instances that are part of the packages to be removed.
		// For each instance, let's find the class and the package it belongs to.
		$componentsList = MoufReflectionProxy::getEnhancedComponentsList($this->selfedit != "false");
		
		$instancesList = $this->moufManager->getInstancesList();
		$pluginsDirectory = $this->moufManager->getFullPathToPluginsDirectory();
		
		$this->toDeleteInstance = array();
		$fullPathToPluginsDirectory = $this->moufManager->getFullPathToPluginsDirectory();
		
		foreach ($instancesList as $instanceName=>$className) {
			if (isset($componentsList[$className])) {
				$fileName = $componentsList[$className]["filename"];
				foreach ($this->moufDependencies as $dependency) {
					if ($this->isPartOfPackage($fileName, $dependency, $fullPathToPluginsDirectory)) {
						$this->toDeleteInstance[$instanceName] = $className;
					}
				}
			}
		}
		
		
		if ((count($this->moufDependencies)>1 || count($this->toDeleteInstance)>0) && $confirm=="false") {
			$this->template->addContentFile("views/packages/displayConfirmPackagesDisable.php", $this);
			$this->template->draw();	
		} else {
			/*if (!array_search($this->package, $this->moufDependencies)) {
				$this->moufDependencies[] = $this->package;
			}*/

			foreach ($this->toDeleteInstance as $instance=>$className) {
				$this->moufManager->removeComponent($instance);
			}
			
			foreach ($this->moufDependencies as $dependency) {
				//var_dump($dependency->getDescriptor()->getPackageXmlPath());
				$this->moufManager->removePackageByXmlFile($dependency->getDescriptor()->getPackageXmlPath());
			}//exit;
			$this->moufManager->rewriteMouf();
						
			$url = "Location: ".ROOT_URL."mouf/packages/?selfedit=".$selfedit."&validation=disable";
			foreach ($this->moufDependencies as $moufDependency) {
				$url.= "&packageList[]=".$moufDependency->getDescriptor()->getPackageDirectory();
			}
			header($url);	
		}
	}
	
	/**
	 * Action that is run to upgrade/downgrade a package.
	 *
	 * @Action
	 * @Logged
	 * @param string $name The path to the package.xml file relative to the plugins directory.
	 * @param string $selfedit
	 * @param string $confirm
	 */
	public function upgradePackage($name, $selfedit = "false", $confirm="false") {
		throw new Exception("Sorry, upgrading packages is not supported yet.");
	}
	
	/**
	 * Returns true if the file "filename" is part of the package "$package".
	 *
	 * @param string $filename
	 * @param MoufPackage $package
	 * @return bool
	 */
	private function isPartOfPackage($filename, MoufPackage $package, $pluginsDirectory) {
		
		$packageFile = realpath($pluginsDirectory."/".$package->getDescriptor()->getPackageXmlPath());
		$dirPackage = dirname($packageFile);
		
		if (strpos($filename, $dirPackage) === 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Display the rows of buttons below the package list.
	 * 
	 * @param MoufPackage $package The package to display
	 * @param string $enabledVersion The version of that package that is currently enabled, if any.
	 */
	function displayPackageActions(MoufPackage $package, $enabledVersion) {
		$packageXmlPath = $package->getDescriptor()->getPackageXmlPath();
		$isPackageEnabled = $this->moufManager->isPackageEnabled($packageXmlPath);
		
		
		if ($enabledVersion !== false && $enabledVersion != $package->getDescriptor()->getVersion()) {
			echo "<form action='upgradePackage' method='POST'>";
			echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
			echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
			if (MoufPackageDescriptor::compareVersionNumber($package->getDescriptor()->getVersion(), $enabledVersion) > 0) {
				echo "<button>Upgrade to this package</button>";
			} else {
				echo "<button>Downgrade to this package</button>";
			}
			echo "</form>";
		} else if (!$isPackageEnabled) {
			echo "<form action='enablePackage' method='POST'>";
			echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
			echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
			echo "<button>Enable</button>";
			echo "</form>";
		} else {
			echo "<form action='disablePackage' method='POST'>";
			echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
			echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
			echo "<button>Disable</button>";
			echo "</form>";
		}
		
	}
	
}
?>