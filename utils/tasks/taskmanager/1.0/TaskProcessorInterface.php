<?php
/**
 * You must implement the TaskProcessorInterface to be 
 * able to run your own tasks.
 * Tasks are bits of code that are run, and retried later
 * if they fail.
 * 
 * @author David Negrier
 */
interface TaskProcessorInterface {
	/**
	 * The run method performs the action on the task.
	 * It returns "true" if the task was run successfully,
	 * or "false" if the task failed (if the task failed,
	 * it will be retried later).
	 * 
	 * @param Task $task
	 * @return boolean
	 * @throws Exception
	 */
	public function run(Task $task);
	
	/**
	 * Returns a string describing the task.
	 * This is used to display the task in the UI.
	 * 
	 * @param Task $task
	 * @return string
	 * @throws Exception
	 */
	public function getTaskName(Task $task);
	
	/**
	 * The getRetryInterval function returns the number of seconds to wait before retrying the task (if the task failed).
	 * The TaskManager will do its best to call the retry. Please note there is no guarantee it will run exactly at
	 * the specified time. The time the task will run depends first on the time the TaskManager is called (by a cron task
	 * or any other mechanism).
	 * 
	 * @param Task $task
	 * @return int
	 * @throws Exception
	 */
	public function getRetryInterval(Task $task);
}
?>