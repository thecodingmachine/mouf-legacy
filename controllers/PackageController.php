<?php
require_once dirname(__FILE__)."/../MoufPackageManager.php";

/**
 * The controller managing the list of packages included in the Mouf plugins directory.
 *
 * @Component
 */
class PackageController extends Controller {

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
	 * The list of dependencies to the selected package.
	 *
	 * @var array<MoufPackage>
	 */
	public $moufDependencies;
	
	/**
	 * Displays the list of component files
	 * 
	 * @Action
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
				
		$packageManager = new MoufPackageManager();
		$this->moufPackageList = $packageManager->getPackagesList();
		$this->template->addContentFile("views/packages/displayPackagesList.php", $this);
		$this->template->draw();	
	}
	
	/**
	 * Action that is run to enable a package.
	 *
	 * @Action
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
		$dependencies = $packageManager->getDependencies($this->package);
		$this->moufDependencies = array();
		// Let's only keep the packages that are not already installed from this list:
		foreach ($dependencies as $moufDependency) {
			$enabled = $this->moufManager->isPackageEnabled($moufDependency->getDescriptor()->getPackageXmlPath());
			if (!$enabled) {
				$this->moufDependencies[] = $moufDependency;
			}
		}
		
		
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
		}
	}
	
}
?>