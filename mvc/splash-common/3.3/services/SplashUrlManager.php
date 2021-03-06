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
	 * @return array<SplashRoute>
	 */
	public static function getUrlsList($selfedit) {
		
		return self::getUrlsByProxy($selfedit);
	}
	
	public static function getUrlsByProxy($selfEdit) {
		// Let's perform a late loading on the SplashRoute class (because the admin version of Mouf might use a different version of the class than the application
		// itself, we cannot include this file directly, since it is used inside the admin of mouf).
		
		// TODO: the proxy should return JSON instead of objects (because Splash is sued both on the admin and on the app side, with different versions)
		require_once dirname(__FILE__)."/SplashRoute.php";
		
		$url = MoufReflectionProxy::getLocalUrlToProject()."plugins/mvc/splash-common/3.3/direct/get_urls_list.php?selfedit=".(($selfEdit)?"true":"false");;

		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
		
	}
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		//curl_setopt( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		//curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		
		// Let's forward all cookies so the session in preserved.
		// Problem: because the session file is locked, we cannot do that without closing the session first
		session_write_close();
		
		$cookieArr = array();
		foreach ($_COOKIE as $key=>$value) {
			$cookieArr[] = $key."=".urlencode($value);
		}
		$cookieStr = implode("; ", $cookieArr);
		curl_setopt($ch, CURLOPT_COOKIE, $cookieStr);
		
		$response = curl_exec( $ch );
		
		// And let's reopen the session...
		session_start();
		
		
		if( curl_error($ch) ) { 
			throw new Exception("An error occured: ".curl_error($ch));
		}
		curl_close( $ch );
		
		return $response;
	}
}


?>