<?php
require_once dirname(__FILE__)."/../MoufPackageManager.php";

/**
 * The controller managing the repository list used to download the packages.
 *
 * @Component
 */
class RepositorySourceController extends Controller {

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
	 * The list of repositories.
	 * 
	 * @var array(array("name"=>string,"url"=>string))
	 */
	public $repositoryUrls = array();

	/**
	 * Lists all the repositories known of Mouf.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		if ($this->moufManager->issetVariable("repositoryUrls")) {
			$this->repositoryUrls = $this->moufManager->getVariable("repositoryUrls");
		}
		
		$this->template->addContentFile("views/packages/displayRepositorySources.php", $this);
		$this->template->draw();	
	}
	
	protected $repositoryId = null;
	
	/**
	 * Display the add screen for repositories.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function add($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		
		
		$this->template->addContentFile("views/packages/editRepository.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Display the edit screen for repositories.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function edit($id, $selfedit = "false") {
		$this->selfedit = $selfedit;
		$this->repositoryId = $id;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		$this->repositoryUrls = $this->moufManager->getVariable("repositoryUrls");
		
		$this->template->addContentFile("views/packages/editRepository.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Saves the repository.
	 * 
	 * @Action
	 * @Logged
	 * @param unknown_type $name
	 * @param unknown_type $url
	 * @param unknown_type $id
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function save($name, $url, $id=null, $selfedit = "false") {
		$this->selfedit = $selfedit;
		$this->repositoryId = $id;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->repositoryUrls = $this->moufManager->getVariable("repositoryUrls");
		
		if ($id !== null) {
			$this->repositoryUrls[$id] = array("name"=>$name, "url"=>$url);
		} else {
			$this->repositoryUrls[] = array("name"=>$name, "url"=>$url);
		}
		
		$this->moufManager->setVariable("repositoryUrls", $this->repositoryUrls);
		$this->moufManager->rewriteMouf();
		header("Location: .?selfedit=".$selfedit);
	}
	
}
?>