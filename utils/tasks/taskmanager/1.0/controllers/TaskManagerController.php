<?php

/**
 * The controller used by the task manager.
 *
 * @Component
 */
class TaskManagerController extends Controller {

	protected $selfedit;
	
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
		$this->selfedit = $selfedit;
		$this->awaitingTasks = self::getAwaitingTasksFromService($selfedit=="true");
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/awaitingTasks.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * @Logged
	 */
	public function deleteTask($id, $taskmanager, $selfedit="false") {
		$this->selfedit = $selfedit;
		self::deleteTaskFromService($id, $taskmanager, $selfedit=="true");
		
		$this->viewAwaitingTasks($selfedit);
	}
	
	/**
	 * Gets the awaiting task list.
	 * 
	 * @param bool $selfEdit
	 * @return boolean
	 * @throws Exception
	 */
	protected static function getAwaitingTasksFromService($selfEdit) {

		$url = MoufReflectionProxy::mouf_get_local_url_to_project()."plugins/utils/tasks/taskmanager/1.0/direct/get_awaiting_tasks.php?selfedit=".(($selfEdit)?"true":"false");
		 
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	/**
	 * Deletes a task from the list.
	 * 
	 * @param int $id
	 * @param bool $selfEdit
	 * //@return boolean
	 * @throws Exception
	 */
	protected static function deleteTaskFromService($id, $taskmanager, $selfEdit) {
		$id = (int) $id;
		$url = MoufReflectionProxy::mouf_get_local_url_to_project()."plugins/utils/tasks/taskmanager/1.0/direct/delete_task.php?id=$id&taskmanager=".urlencode($taskmanager)."&selfedit=".(($selfEdit)?"true":"false");
		 
		$response = self::performRequest($url);

		if (!empty($response)) {
			throw new Exception("An error occured while deletin a task:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		/*$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;*/
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
