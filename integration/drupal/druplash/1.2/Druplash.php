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
		$urlsList = SplashUtils::getSplashUrlManager()->getUrlsList(false);
		
		foreach ($urlsList as $urlCallback) {
			/* @var $urlCallback SplashCallback */
			
			$url = $urlCallback->url;
			// remove trailing slash
			$url = rtrim($url, "/");
			
			$title = 'Action '.$urlCallback->methodName.' for controller '.$urlCallback->controllerInstanceName;
			if ($urlCallback->title !== null) {
				$title = $urlCallback->title ;
			}
			
			/*$items[$url] = array(
			    'title' => $title,
			    'page callback' => 'druplash_execute_action',
			    'access arguments' => array('access content'),
				'page arguments' => array($urlCallback->controllerInstanceName, $urlCallback->methodName),
			    'type' => MENU_CALLBACK
			);*/
			
			$httpMethods = $urlCallback->httpMethods;
			if (empty($httpMethods)) {
				$httpMethods[] = "default";
			}
			
			foreach ($httpMethods as $httpMethod) {
			
				if (isset($items[$url])) {
					// FIXME: support different 'access arguments' for different HTTP methods!
					$items[$url]['page arguments'][$httpMethod] = array($urlCallback->controllerInstanceName, $urlCallback->methodName);
				} else {
					$items[$url] = array(
					    'title' => $title,
					    'page callback' => 'druplash_execute_action',
					    'access arguments' => array('access content'),
						'page arguments' => array(array($httpMethod => array("instance"=>$urlCallback->controllerInstanceName, "method"=>$urlCallback->methodName))),
					    'type' => MENU_CALLBACK
					);
				}
			}
			
		}
		
		return $items;
	}
	
	/**
	 * Executes an action.
	 * This method is triggered from the Druplash menu hook.
	 * 
	 * @param string $actions
	 */
	public static function executeAction($actions) {
		$httpMethod = $_SERVER['REQUEST_METHOD'];
		
		if (isset($actions[$httpMethod])) {
			$action = $actions[$httpMethod];
		} elseif (isset($actions["default"])) {
			$action = $actions["default"];
		} else {
			drupal_not_found();
		}
		
		$controller = MoufManager::getMoufManager()->getInstance($action['instance']);
		return $controller->callAction($action['method']);
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