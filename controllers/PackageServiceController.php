<?php
require_once dirname(__FILE__)."/../MoufPackageManager.php";

/**
 * The controller in charge of making accessible the packages of this Mouf application to other Mouf installations.
 * Most actions in this controller return a JSON message.
 * It is only used when Mouf is configured to act as a repository (see mouf/config.php file).
 *
 * @Component
 */
class PackageServiceController extends Controller {

	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;

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
	 * Returns the list of all installed packages.
	 * 
	 * @Action
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 * @param string $validation The validation message to display (either null, or enable or disable).
	 * @param array<string> $packageList The array of packages enabled or disabled.
	 */
	public function defaultAction() {
		
		if (ACT_AS_REPOSITORY != true) {
			$array = array("error"=>"The Mouf instance you are trying to access does not allow remote access.");
			echo json_encode($array);
			exit;
		}
		
		$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		
		$packageManager = new MoufPackageManager();
		
		$this->moufPackageRoot = $packageManager->getOrderedPackagesList();
		
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		// TODOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
		$result = $this->moufPackageRoot->getSimplifiedArray();
		echo "toto";
		
		
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
	
}
?>