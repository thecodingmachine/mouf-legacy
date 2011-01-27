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
		
		$fileName = $actionDescriptor->params['packageFile'];
		if (strpos($fileName, "/") === 0) {
			$fileName = substr($fileName, 1);
		}
		
		if (!file_exists(ROOT_PATH."plugins/".$fileName)) {
			throw new MoufException("Unable to enable package: the file plugins/".$fileName." does not exist.");
		}
		
		$moufManager->addPackageByXmlFile($fileName);
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