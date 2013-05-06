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
	 * @return Task
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
		
		$query = "INSERT INTO ".$this->tableName." (instance_name, params, status, created_date, last_try_date, next_try_date, nbtries, last_output)
			VALUES (".$this->dbConnection->quoteSmart($instanceName).",
					 ".$this->dbConnection->quoteSmart($serializedParams).", 
					 'todo', 
					 ".$this->dbConnection->quoteSmart($now).", 
					 null, 
					 ".$this->dbConnection->quoteSmart($nextDate).",
					 0, 
					 null);";
		
		// TODO: are we sure we return the right task?
		try {
			$startedTransaction = false; 
			if (!$this->dbConnection->hasActiveTransaction()) {
				$startedTransaction = true;
				$this->dbConnection->beginTransaction();
			}
			$this->dbConnection->exec($query);
			$taskId = $this->dbConnection->getInsertId($this->tableName, 'id');
			if ($startedTransaction) {
				$this->dbConnection->commit();
			}
		} catch (Exception $e) {
			try {
				if ($startedTransaction) {
					$this->dbConnection->rollback();
				}
			} catch (Exception $e2) {
				// Ignore any further exception.
			}
			throw $e;
		}
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
		$query = "SELECT id, instance_name, params, status, created_date, last_try_date, next_try_date, nbtries, last_output FROM ".$this->tableName." WHERE id = ".$this->dbConnection->quoteSmart($id);
		
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
		$lastTryDate = $task->getLastTryDate();
		if ($lastTryDate != null) {
			$lastTryDate = date('c', $lastTryDate);
		}
		$nextTryDate = $task->getNextTryDate();
		if ($nextTryDate != null) {
			$nextTryDate = date('c', $nextTryDate);
		}
		
		$query = "UPDATE ".$this->tableName." SET instance_name = ".$this->dbConnection->quoteSmart($task->getTaskProcessorName()).",
					params = ".$this->dbConnection->quoteSmart($serializedParams).",
					status = ".$this->dbConnection->quoteSmart($task->getStatus()).",
					last_try_date = ".$this->dbConnection->quoteSmart($lastTryDate).",
					next_try_date = ".$this->dbConnection->quoteSmart($nextTryDate).",
					nbtries = '".$task->getNbTries()."',
					last_output = ".$this->dbConnection->quoteSmart(substr($task->getLastOutput(),0,500))."
					WHERE id = ".$this->dbConnection->quoteSmart($task->getId());
		$this->dbConnection->exec($query);
	}
	
	/**
	 * Save the current task (Public)
	 * 
	 * @param Task $task
	 */
	public function saveTaskPublic(Task $task) {
		$this->saveTask($task);
	}
	
	/**
	 * Runs the task passed in parameter immediately
	 * 
	 * @param Task $task
	 */
	private function runTask(Task $task) {
		$this->logger->trace("Running task '".$task->getId()."' with task processor '".$task->getTaskProcessorName()."'");
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
	 * Runs the task passed in parameter immediately
	 *
	 * @param Task $task
	 */
	public function runTaskPublic(Task $task) {
		$this->runTask($task);
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
	 * Returns the Exception Backtrace as a text string.
	 *
	 * @param unknown_type $backtrace
	 * @return unknown
	 */
	static private function getTextBackTrace($backtrace) {
		$str = '';

		foreach ($backtrace as $step) {
			if ($step['function']!='getTextBackTrace' && $step['function']!='handle_error')
			{
				if (isset($step['file']) && isset($step['line'])) {
					$str .= "In ".$step['file'] . " at line ".$step['line'].": ";
				}
				if (isset($step['class']) && isset($step['type']) && isset($step['function'])) {			
					$str .= $step['class'].$step['type'].$step['function'].'(';
				}

				if (is_array($step['args'])) {
					$drawn = false;
					$params = '';
					foreach ( $step['args'] as $param)
					{
						$params .= self::getPhpVariableAsText($param);
						//$params .= var_export($param, true);
						$params .= ', ';
						$drawn = true;
					}
					$str .= $params;
					if ($drawn == true)
					$str = substr($str, 0, strlen($str)-2);
				}
				$str .= ')';
				$str .= "\n";
			}
		}

		return $str;
	}
	
	/**
	 * Used by the debug function to display a nice view of the parameters.
	 *
	 * @param unknown_type $var
	 * @return unknown
	 */
	private static function getPhpVariableAsText($var) {
		if( is_string( $var ) )
		return( '"'.str_replace( array("\x00", "\x0a", "\x0d", "\x1a", "\x09"), array('\0', '\n', '\r', '\Z', '\t'), $var ).'"' );
		else if( is_int( $var ) || is_float( $var ) )
		{
			return( $var );
		}
		else if( is_bool( $var ) )
		{
			if( $var )
			return( 'true' );
			else
			return( 'false' );
		}
		else if( is_array( $var ) )
		{
			$result = 'array( ';
			$comma = '';
			foreach( $var as $key => $val )
			{
				$result .= $comma.self::getPhpVariableAsText( $key ).' => '.self::getPhpVariableAsText( $val );
				$comma = ', ';
			}
			$result .= ' )';
			return( $result );
		}

		elseif (is_object($var)) return "Object ".get_class($var);
		elseif(is_resource($var)) return "Resource ".get_resource_type($var);
		return "Unknown type variable";
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
			FROM ".$this->tableName." WHERE (status = 'todo' OR status='retrying') AND next_try_date <= ".$this->dbConnection->quoteSmart($now);
		$results = $this->dbConnection->getAll($query);
		
		foreach ($results as $result) {
			$array[] = $this->getTaskFromRow($result);
		}
		return $array;
	}
	
	/**
	 * Returns the number of tasks that have been tried, that are in error, and that will be retried later.
	 * 
	 * @return int
	 */
	public function getNbTasksInError() {
		$now = date('c', time());
		$query = "SELECT count(1) 
			FROM ".$this->tableName." WHERE status='retrying' AND next_try_date <= ".$this->dbConnection->quoteSmart($now);
		$result = $this->dbConnection->getOne($query);
		return $result;
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