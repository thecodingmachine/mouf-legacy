<?php

/**
 * This class is in charge of retrieving the URLs that can be accessed.
 *  
 * @author David
 */
class SplashUrlManager {
	
	/**
	 * Returns the list of URLs that can be accessed, and the function/method that should be called when the URL is called.
	 * 
	 * @return array<SplashCallback>
	 */
	
	public static function getUrlsList($selfedit) {
		
		//if ($selfedit == "true") {
			$moufManager = MoufManager::getMoufManager();
		/*} else {
			$moufManager = MoufManager::getMoufManagerHiddenInstance();
		}*/
		
		
		$instanceNames = MoufReflectionProxy::getInstances("UrlProviderInterface", $selfedit);
		
		$urls = array();
		
		foreach ($instanceNames as $instanceName) {
			// FIXME provide a full service in Proxy! Otherwise, it cannot work in admin
			$urlProvider = $moufManager->getInstance($instanceName);
			/* @var $urlProvider UrlProviderInterface */
			$urls += $urlProvider->getUrlsList();
		}
		
		return $urls;
	} 
}


?>