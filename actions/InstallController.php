<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 

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
	 * The exception thrown while running the action, if any.
	 * @var unknown_type
	 */
	protected $exception;
	
	protected $selfedit;
	
	/**
	 * Displays the page that runs the actions of the MultiStepActionService.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
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
	public function nextstep($selfedit = "false") {
		// FIXME: we should take into account the $selfEdit variable!!!!
		// TODO: first, we should receive the selfedit variable!!!!
		$this->done = false;
		$actionResult =  null;
		$html = "";
		if ($this->multiStepActionService->hasRemainingAction()) {
			try {
				$actionResult = $this->multiStepActionService->executeNextAction();
			} catch (Exception $e) {
				$this->exception = $e;
			}
			if (!$this->multiStepActionService->hasRemainingAction()) {
				$this->done = true;
			}
		} else {
			$this->done = true;
		}
		
		$redirect = null;
		if ($actionResult && $actionResult->getStatus() == "redirect") {
			$redirect = $actionResult->getRedirectUrl();
		}
		
		if ($this->done) {
			$this->multiStepActionService->purgeActions();
		}
		
		if (!$redirect) {
			ob_start();
			$this->actionsList = $this->multiStepActionService->getActionsList();
			include dirname(__FILE__)."/views/displaySteps.php";
			$html = ob_get_contents();
			ob_end_clean();	
		}
		
		if ($this->exception) {	
			echo json_encode(array("code"=>"error", "html"=>$html));
		} elseif ($redirect) {
			echo json_encode(array("code"=>"redirect", "redirect"=>$redirect));
		} else {
			echo json_encode(array("code"=>($this->done?"finished":"continue"), "html"=>$html));
		}
		
		// TODO: prévoir un message "OK" à la fin du process (avant le redirect, éventuellement).
	}
	
	/**
	 * Splash action called at the end of an "install" action to validate the action and continue. 
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit 
	 */
	public function installStepDone($selfedit = "false") {
		$this->multiStepActionService->validateCurrentAction();
		header("Location: .?selfedit=".$selfedit);
	}
}