<?php


/**
 * The controller managing the list of developer components included in the Mouf app.
 *
 * @Component
 */
class ComponentsController extends Controller {

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
				
		$template = $this->template;
		//$this->template->addJsFile(ROOT_URL."mouf/views/displayComponent.js");
		$template->addContentFile("views/displayComponentList.php", $this);
		$template->draw();	
	}
	
	/**
	 * Saves the list of files to be edited
	 * 
	 * @Action
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function save($files = array(), $selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->moufManager->setRegisteredComponentFiles($files);
		
		
		$this->moufManager->rewriteMouf();
		
		header("Location: ".ROOT_URL."mouf/components/?selfedit=".$selfedit);
	}
	
}
?>