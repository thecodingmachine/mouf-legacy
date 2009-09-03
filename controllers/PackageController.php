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
	 * The list of packages found on the system.
	 *
	 * @var array<MoufPackage>
	 */
	public $moufPackageList;
	
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
	
	
	
}
?>