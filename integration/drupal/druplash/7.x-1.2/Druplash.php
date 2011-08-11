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
		
		$items = array();
		
		foreach ($urlsList as $urlCallback) {
			/* @var $urlCallback SplashCallback */
			
			$url = $urlCallback->url;
			// remove trailing slash
			$url = rtrim($url, "/");
			
			$title = 'Action '.$urlCallback->methodName.' for controller '.$urlCallback->controllerInstanceName;
			if ($urlCallback->title !== null) {
				$title = $urlCallback->title ;
			}
			
			// Recover function filters
			$phpDocComment = new MoufPhpDocComment($urlCallback->fullComment);
			$requiresRightArray = $phpDocComment->getAnnotations('RequiresRight');
			$accessArguments = array();
			if(count($requiresRightArray)) {
				foreach ($requiresRightArray as $requiresRight) {
					/* @var $requiresRight RequiresRight */
					$accessArguments[] = $requiresRight->getName();
				}
			} else {
				$accessArguments[] = 'access content';
			}
			
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
					    'access arguments' => $accessArguments,
						'page arguments' => array(array($httpMethod => array("instance"=>$urlCallback->controllerInstanceName, "method"=>$urlCallback->methodName))),
					    'type' => MENU_VISIBLE_IN_BREADCRUMB
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
		
		$instanceNames = MoufReflectionProxy::getInstances("DrupalDynamicBlockInterface", false);
		
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
	
	/**
	 * Set user information in Druplash SESSION.
	 * 
	 * @param array $edit
	 * @param stdClass $account
	 */
	public static function onUserLogin($edit, $account) {
		//TODO: an admin page will be necessary to select which user service instance to use.
		$moufManager = MoufManager::getMoufManager();
		if($moufManager->instanceExists('userService')) {
			$userService = $moufManager->getInstance('userService');
			/* @var $userService MoufUserService */
			$userService->login($account->name, $edit['values']['pass']);
		}
	}
	
	/**
	 * Remove user information in Druplash SESSION.
	 * 
	 * @param stdClass $account
	 */
	public static function onUserLogout($account) {
		//TODO: an admin page will be necessary to select which user service instance to use.
		$moufManager = MoufManager::getMoufManager();
		if($moufManager->instanceExists('userService')) {
			$userService = $moufManager->getInstance('userService');
			/* @var $userService MoufUserService */
			$userService->logoff();
		}
	}
	
	/**
	 * Returns all permissions for hook_permission.
	 * 
	 */
	public static function getPermissions() {
		//TODO: an admin page will be necessary to select which right service instance to use.
		$moufManager = MoufManager::getMoufManager();
		if($moufManager->instanceExists('rightsService')) {
			$rightsService = $moufManager->getInstance('rightsService');
			if($rightsService instanceof DruplashRightService) {
				/* @var $rightsService DruplashRightService */
				return $rightsService->getDrupalPermissions();
			} else 
				return array();
		} else {
			return array();
		}
	}
}

?>