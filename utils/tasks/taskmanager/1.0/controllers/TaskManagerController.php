<?php

/**
 * The controller used by the task manager.
 *
 * @Component
 */
class TaskManagerController extends Controller {

	/**
	 * The default template to use for this controller (will be the mouf template)
	 *
	 * @Property
	 * @Compulsory 
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction() {
		$this->template->addContentFile(dirname(__FILE__)."/../views/cron.php", $this);
		$this->template->draw();
	}
	
	protected $awaitingTasks;
	
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * @Logged
	 */
	public function viewAwaitingTasks($selfedit="false") {
		$this->awaitingTasks = $this->getAwaitingTasksFromService($selfedit=="true");
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/awaitingTasks.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Gets the awaiting task list.
	 * 
	 * @param bool $selfEdit
	 * @return boolean
	 * @throws Exception
	 */
	protected static function getAwaitingTasksFromService($selfEdit) {

		$url = "http://127.0.0.1:".$_SERVER['SERVER_PORT'].ROOT_URL."plugins/utils/tasks/taskmanager/1.0/direct/get_awaiting_tasks.php?selfedit=".(($selfEdit)?"true":"false");
		 
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		$response = curl_exec( $ch );
		
		if( curl_error($ch) ) { 
			throw new Exception("An error occured: ".curl_error($ch));
		}
		curl_close( $ch );
		
		return $response;
	}
}

?>
