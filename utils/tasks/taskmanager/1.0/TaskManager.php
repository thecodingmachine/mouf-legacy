<?php

/**
 * A Task Manager is an object in charge of managing tasks.
 * You can use it to create new tasks, edit them, etc...
 * 
 * @author David Negrier
 * @Component
 */
class TaskManager {
	
	/**
	 * The connection to the database that contains the list of the tasks.
	 * 
	 * @Property
	 * @Compulsory
	 * @var DB_ConnectionInterface
	 */
	public $dbConnection;
	
	/**
	 * The name of the table containing the list of tasks.
	 * Defaults to "tasks".
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $tableName = "tasks";
	
	/**
	 * The logger that will log all actions performed by the TaskManager.
	 * 
	 * @Property
	 * @Compulsory
	 * @var LogInterface
	 */
	public $logger;
	
	/**
	 * Creates a new task.
	 * The task will be processed by the $taskProcessor processor.
	 * Warning! This processor must be registered as a Mouf instance.
	 * The task will have the $params parameters.
	 * It will be triggered at $date (a timestamp).
	 * If no date is specified, it will be triggered right away (and retried
	 * later if it fails).
	 * 
	 * @param TaskProcessorInterface $taskProcessor
	 * @param mixed $params
	 * @param timestamp $date
	 */
	public function createTask(TaskProcessorInterface $taskProcessor, $params, $date = null) {
		$instanceName = MoufManager::getMoufManager()->findInstanceName($taskProcessor);
		$this->logger->trace("Creating a new task for task processor '".$instanceName."'");
		$serializedParams = serialize($params);
		$now = date("c");
		if ($date == null) {
			$nextDate = $now;
		} else {
			$nextDate = date("c", $date);
		}
		
		$query = "INSERT INTO ".$tableName." (instance_name, params, status, created_date, last_try_date, next_try_date, nbtries, last_output)
			VALUES (".$this->dbConnection->quoteSmart($instanceName).",
					 ".$this->dbConnection->quoteSmart($serializedParams).", 
					 'todo', 
					 ".$this->dbConnection->quoteSmart($now).", 
					 null, 
					 ".$this->dbConnection->quoteSmart(next_try_date).",
					 0, 
					 null);";
		
		// TODO: are we sure we return the right task?
		$this->dbConnection->beginTransaction();
		$this->dbConnection->exec($query);
		$taskId = $this->dbConnection->getInsertId($tableName, 'id');
		$this->dbConnection->commit();
		$task = $this->getTask($taskId);
		
		// If we must run the task now, let's do it!
		if ($date == null || $date <= time()) {
			$this->runTask($task);
		}
		return $task;
	}
	
	/**
	 * Returns the task identified bt its unique ID.
	 * 
	 * @param int $id
	 * @return Task
	 */
	public function getTask($id) {
		$query = "SELECT id, instance_name, params, status, created_date, last_try_date, next_try_date, nbtries, last_output FROM tasks WHERE id = '".$this->dbConnection->quoteSmart($id)."'";
		
		$results = $this->dbConnection->getAll($query);
		$result = $results[0];
		
		return $this->getTaskFromRow($result);
	}
	
	private function getTaskFromRow($result) {
		$task = new Task();
		$task->setId($result['id']);
		$task->setTaskProcessorName($result['instance_name']);
		$params = $result['params'];
		if (!empty($params)) {
			$task->setParams(unserialize($params));
		}
		$task->setStatus($result['status']);
		$task->setCreatedDate(strtotime($result['created_date']));
		$task->setLastTryDate(strtotime($result['last_try_date']));
		$task->setNextTryDate(strtotime($result['next_try_date']));
		$task->setNbTries($result['nbtries']);
		$task->setLastOutput($result['last_output']);
		
		return $task;
	}
	
	/**
	 * Saves the current task.
	 * 
	 * @param Task $task
	 */
	protected function saveTask(Task $task) {
		$serializedParams = serialize($task->getParams());
		$query = "UPDATE tasks SET instance_name = ".$this->dbConnection->quoteSmart($task->getTaskProcessorName()).",
					params = ".$this->dbConnection->quoteSmart($serializedParams).",
					status = ".$this->dbConnection->quoteSmart($task->getStatus()).",
					last_try_date = ".$this->dbConnection->quoteSmart($task->getgetLastTryDate()).",
					next_try_date = ".$this->dbConnection->quoteSmart($task->getNextTryDate()).",
					nbtries = '".$task->getNbTries()."',
					last_output = ".$this->dbConnection->quoteSmart($task->getLastOutput())."
					WHERE id = ".$this->dbConnection->quoteSmart($id);
		
		$this->dbConnection->exec($query);		
	}
	
	/**
	 * Runs the task passed in parameter immediately
	 * 
	 * @param Task $task
	 */
	private function runTask(Task $task) {
		$this->logger->trace("Running task task  '".$task->id."' with task processor '".$task->getTaskProcessorName()."'");
		ob_start();
		
		/* @var $taskProcessor TaskProcessorInterface */
		$taskProcessor = MoufManager::getMoufManager()->getInstance($task->getTaskProcessorName());
		try {
			$result = $taskProcessor->run($task);
			$output = ob_get_contents();
		} catch (Exception $e) {
			$result = false;
			$output = ob_get_contents();
			$output .= self::getTextForException($e);
		}
		ob_end_clean();
		
		$task->setNbTries($task->getNbTries()+1);
		$task->setLastOutput($output);
		$task->setLastTryDate(time());
		
		if ($result) {
			$task->setStatus("done");
			$task->setNextTryDate(null);
		} else {
			$task->setStatus("retrying");
			$nextDate = time() + $taskProcessor->getRetryInterval($task);
			$task->setNextTryDate($nextDate);
		}
		$this->saveTask($task);
	}
	
	/**
	 * Function called to display an exception if it occurs.
	 * It will make sure to purge anything in the buffer before calling the exception displayer.
	 *
	 * @param Exception $exception
	 */
	private static function getTextForException(Exception $exception) {
		// Now, let's compute the same message, but without the HTML markup for the error log.
		$textTrace = "Message: ".$exception->getMessage()."\n";
		$textTrace .= "File: ".$exception->getFile()."\n";
		$textTrace .= "Line: ".$exception->getLine()."\n";
		$textTrace .= "Stacktrace:\n";
		$textTrace .= self::getTextBackTrace($exception->getTrace());
		return $textTrace;
	}
	
	/**
	 * Runs all tasks that are awaiting to be run.
	 */
	public function runAllTasks() {
		$tasks = $this->getAwaitingTasks();
		
		// TODO: implement a lock mechanism for very long tasks.
		foreach ($tasks as $task) {
			$this->runTask($task);
		}
	}
	
	/**
	 * Returns the list of all awaiting tasks.
	 * 
	 * @return array<Task>
	 */
	public function getAwaitingTasks() {
		$array = array();
		$now = date('c', time());
		$query = "SELECT id, instance_name, params, status, created_date, last_try_date, next_try_date, nbtries, last_output 
			FROM tasks WHERE (status = 'todo' OR status='retrying') AND next_try_date <= ".$this->dbConnection->quoteSmart($now);
		$results = $this->dbConnection->getAll($query);
		
		foreach ($results as $result) {
			$array[] = $this->getTaskFromRow($result);
		}
		return $array;
	}
	
	/**
	 * Disables the task passed in parameter.
	 * It will no longer be executed.
	 * 
	 * @param Task $task
	 */
	public function disableTask(Task $task) {
		$task->setStatus("disabled");
		$this->saveTask($task);
	}
}