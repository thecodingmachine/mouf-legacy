<?php

/**
 * An action that download a package.
 * 
 * @author david
 * @Component
 */
class DownloadPackageAction implements MoufActionProviderInterface {
	
	/**
	 * @Property
	 * @Compulsory
	 * @var MoufPackageDownloadService
	 */
	public $packageDownloadService;
	
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
		$this->packageDownloadService->setMoufManager($moufManager);
		
		$respositoryUrl = $actionDescriptor->params['repositoryUrl'];
		$group = $actionDescriptor->params['group'];
		$name = $actionDescriptor->params['name'];
		$version = $actionDescriptor->params['version'];
		
		$repository = $this->packageDownloadService->getRepository($respositoryUrl);
		$this->packageDownloadService->downloadAndUnpackPackage($repository, $group, $name, $version);
	}
	
	/**
	 * Returns the text describing the action.
	 * 
	 * @param MoufActionDescriptor $actionDescriptor
	 */
	public function getName(MoufActionDescriptor $actionDescriptor) {
		return "Downloading package ".$actionDescriptor->params['group']."/".$actionDescriptor->params['name']."/".$actionDescriptor->params['version']." from repository ".$actionDescriptor->params['repositoryUrl'];
	}
	
}