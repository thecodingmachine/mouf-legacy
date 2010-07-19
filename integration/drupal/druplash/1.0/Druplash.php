<?php

/**
 * Main class in charge of routing
 * @author David
 *
 */
class Druplash {
	/**
	 * Returns the list of URLs expected by hook_menu.
	 */
	public static function getDrupalMenus() {
		$urlsList = SplashUrlManager::getUrlsList(false);
		
		foreach ($urlsList as $urlCallback) {
			/* @var $urlCallback SplashCallback */
			
			$url = $urlCallback->url;
			// remove trailing slash
			$url = rtrim($url, "/");
			
			$items[$url] = array(
			    'title' => 'Action '.$urlCallback->methodName.' for controller '.$urlCallback->controllerInstanceName,
			    'page callback' => 'druplash_execute_action',
			    'access arguments' => array('access content'),
				'page arguments' => array($urlCallback->controllerInstanceName, $urlCallback->methodName),
			    'type' => MENU_CALLBACK
			);
			
		}
		
		return $items;
	}
	
	/**
	 * Executes an action.
	 * This mmethod is triggered from the Drusplash menu hook.
	 * 
	 * @param string $instanceName
	 * @param string $actionName
	 */
	public static function executeAction($instanceName, $actionName) {
		$controller = MoufManager::getMoufManager()->getInstance($instanceName);
		return $controller->callAction($actionName);
	}
	
	/**
	 * Returns a list of blocks.
	 * This will return a list of all DrupalDynamicBlock instances to Drupal's hook_block
	 * (in the format described at http://api.drupal.org/api/function/hook_block/6)
	 */
	public static function getDrupalBlocks() {
		$moufManager = MoufManager::getMoufManager();
		
		$instanceNames = MoufReflectionProxy::getInstances("DrupalDynamicBlockInterface", $selfedit);
		
		$blocks = array();
		
		foreach ($instanceNames as $instanceName) {
			$moufBlock = $moufManager->getInstance($instanceName);
			/* @var $moufBlock DrupalDynamicBlockInterface */
			$block = array("info"=>$moufBlock->getName(),
							"cache"=>(int)$moufBlock->getCache(),
							'weight'=>$moufBlock->getWeight(), 
							'status'=>$moufBlock->getStatus(),
							'region'=>$moufBlock->getRegion(),
							'visibility'=>$moufBlock->getVisibility(),
							'pages'=>$moufBlock->getPages());
			
			$blocks[$instanceName] = $block;
		}
		return $blocks;
	}
	
	/**
	 * Returns a Drupal node in the format expected for Drupal hooks.
	 * 
	 * @param string $instanceName
	 */
	public static function getDrupalBlock($instanceName) {
		$moufManager = MoufManager::getMoufManager();
		$moufBlock = $moufManager->getInstance($instanceName);
		/* @var $moufBlock DrupalDynamicBlock */
		return array('subject'=>$moufBlock->getSubject(),
					'content'=>$moufBlock->getContent());
	}
}

?>