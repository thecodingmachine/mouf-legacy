<?php

/**
 * An action that enables a package.
 * 
 * @author david
 * @Component
 */
class EnablePackageAction implements MoufActionProviderInterface {
	/**
	 * Executes the action passed in parameter.
	 * 
	 * @param MoufActionDescriptor $actionDescriptor
	 */
	public function execute(MoufActionDescriptor $actionDescriptor) {
		if ($actionDescriptor->selfEdit == true) {
			$moufManager = MoufManager::getMoufManager();
		} else {
			$moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		$moufManager->addPackageByXmlFile($actionDescriptor->params['packageFile']);
		$moufManager->rewriteMouf();
	}
	
	/**
	 * Returns the text describing the action.
	 * 
	 * @param MoufActionDescriptor $actionDescriptor
	 */
	public function getName(MoufActionDescriptor $actionDescriptor) {
		return "Enabling package ".$actionDescriptor->params['packageFile'].".";
	}
	
}