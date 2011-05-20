<?php

class SplashUtils {
	
	/**
	 * 
	 * @return SplashUrlManager
	 */
	public static function getSplashUrlManager() {
		// Performs some late loading to avoid problems with the Mouf admin
		require_once 'SplashUrlManager.php';
		
		return new SplashUrlManager();
	}
}

?>