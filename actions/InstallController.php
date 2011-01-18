<?php

/**
 * The controller that performs the installation by running the MultiStepActionService in a loop.
 * 
 * @author david
 * @Component
 */
class InstallController extends Controller {
	
	/**
	 * @Property
	 * @Compulsory
	 * @var MultiStepActionService
	 */
	public $multiStepActionService;
	
	/**
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * 
	 * @var array<MoufActionDescriptor>
	 */
	protected $actionsList;
	
	/**
	 * True if finished.
	 * @var bool
	 */
	protected $done;
	
	/**
	 * Displays the page that runs the actions of the MultiStepActionService.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit 
	 */
	public function defaultAction($selfedit = "false") {
		$this->actionsList = $this->multiStepActionService->getActionsList();
		$this->template->addContentFile(dirname(__FILE__)."/views/install.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Ajax action that installs the next step.
	 * 
	 * @Action
	 * @Logged
	 */
	public function nextstep() {
		$this->done = false;
		if ($this->multiStepActionService->hasRemainingAction()) {
			try {
				$this->multiStepActionService->executeNextAction();
			} catch (Exception $e) {
				
			}
			if (!$this->multiStepActionService->hasRemainingAction()) {
				$this->done = true;
			}
		} else {
			$this->done = true;
		}
		
		if ($this->done) {
			$this->multiStepActionService->purgeActions();
		}
		
		ob_start();
		$this->actionsList = $this->multiStepActionService->getActionsList();
		include dirname(__FILE__)."/views/displaySteps.php";
		$html = ob_get_contents();
		ob_end_clean();
		
		echo json_encode(array("code"=>($this->done?"finished":"continue"), "html"=>$html));
		// TODO: prévoir un message "OK" à la fin du process (avant le redirect, éventuellement).
	}
}