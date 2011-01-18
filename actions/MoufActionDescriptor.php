<?php

/**
 * A simple object describing an action to be executed by the MultiStepActionService.
 * 
 * @author david
 */
class MoufActionDescriptor {
	
	/**
	 * The action provider (the instanceName of a class implementing the MoufActionProviderInterface).
	 * 
	 * @var string
	 */
	public $actionProviderName;
	
	/**
	 * The parameters to be passed to the action provider (must be serializable)
	 * 
	 * @var mixed
	 */
	public $params;
	
	/**
	 * The status (one of: "todo", "done", "error")
	 * 
	 * @var string
	 */
	public $status;
	
	/**
	 * Whether this action is to be executed in selfedit mode or not.
	 * 
	 * @var bool
	 */
	public $selfEdit;
	
	public function __construct($actionProviderName, $params, $status, $selfEdit = false) {
		$this->actionProviderName = $actionProviderName;
		$this->params = $params;
		$this->status = $status;
		$this->selfEdit = $selfEdit;
	}
	
	/**
	 * Runs the action!
	 * The action should not return anything. It can throw an error in case a problem is detected.
	 */
	public function execute() {
		// An action is always executed in the MoufAdmin scope.
		$moufManager = MoufManager::getMoufManager();
		$actionProvider = $moufManager->getInstance($this->actionProviderName);
		/* @var $actionProvider MoufActionProvider */
		$actionProvider->execute($this);
	}
	
	/**
	 * Returns the name for the action.
	 * @return string
	 */
	public function getName() {
		// An action is always executed in the MoufAdmin scope.
		$moufManager = MoufManager::getMoufManager();
		$actionProvider = $moufManager->getInstance($this->actionProviderName);
		/* @var $actionProvider MoufActionProvider */
		return $actionProvider->getName($this);
	}
}