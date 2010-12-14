<?php

/**
 * A Task represent an action to do, at some point in time, and that will be retried if the action fails.
 * 
 * @author David Négrier
 */
class Task {
	
	private $id;
	private $taskProcessorName;
	private $params;
	private $status;
	private $createdDate;
	private $lastTryDate;
	private $nextTryDate;
	private $nbTries;
	private $lastOutput;
	
	/**
	 * Returns the ID of the task
	 * 
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the ID of the task
	 * 
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * Returns the Mouf instance name of the task processor
	 * 
	 * @return int
	 */
	public function getTaskProcessorName() {
		return $this->taskProcessorName;
	}
	
	/**
	 * Sets the Mouf instance name of the task processor
	 * 
	 * @param string $taskProcessorName
	 */
	public function setTaskProcessorName($taskProcessorName) {
		$this->taskProcessorName = $taskProcessorName;
	}
	
	/**
	 * Returns the status
	 * One of: "done", "todo", "retrying" (failed, retrying the task), "disabled"
	 * 
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Sets the status.
	 * One of: "done", "todo", "retrying" (failed, retrying the task), "disabled"
	 * 
	 * @param string $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
	
	/**
	 * Returns the params
	 * 
	 * @return mixed
	 */
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * Sets the params
	 * 
	 * @param mixed $params
	 */
	public function setParams($params) {
		$this->params = $params;
	}
	
	/**
	 * Returns the createdDate
	 * 
	 * @return timestamp
	 */
	public function getCreatedDate() {
		return $this->createdDate;
	}
	
	/**
	 * Sets the createdDate
	 * 
	 * @param timestamp $createdDate
	 */
	public function setCreatedDate($createdDate) {
		$this->createdDate = $createdDate;
	}
	
	/**
	 * Returns the lastTryDate
	 * 
	 * @return timestamp
	 */
	public function getLastTryDate() {
		return $this->lastTryDate;
	}
	
	/**
	 * Sets the lastTryDate
	 * 
	 * @param timestamp $lastTryDate
	 */
	public function setLastTryDate($lastTryDate) {
		$this->lastTryDate = $lastTryDate;
	}
	
	/**
	 * Returns the nextTryDate
	 * 
	 * @return timestamp
	 */
	public function getNextTryDate() {
		return $this->nextTryDate;
	}
	
	/**
	 * Sets the nextTryDate
	 * 
	 * @param timestamp $nextTryDate
	 */
	public function setNextTryDate($nextTryDate) {
		$this->nextTryDate = $nextTryDate;
	}
	
	/**
	 * Returns the nbTries
	 * 
	 * @return int
	 */
	public function getNbTries() {
		return $this->nbTries;
	}
	
	/**
	 * Sets the nbTries
	 * 
	 * @param string $nbTries
	 */
	public function setNbTries($nbTries) {
		$this->nbTries = $nbTries;
	}

	/**
	 * Returns the lastOutput
	 * 
	 * @return string
	 */
	public function getLastOutput() {
		return $this->lastOutput;
	}
	
	/**
	 * Sets the lastOutput
	 * 
	 * @param string $lastOutput
	 */
	public function setLastOutput($lastOutput) {
		$this->lastOutput = $lastOutput;
	}
	
}