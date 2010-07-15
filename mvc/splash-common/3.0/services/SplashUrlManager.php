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
		return self::getUrlsByProxy($selfedit);
	}
	
	private static function getUrlsByProxy($selfEdit) {
		$url = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].ROOT_URL."plugins/mvc/splash-common/3.0/direct/get_urls_list.php?selfedit=".(($selfEdit)?"true":"false");;

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
		
		$response = curl_exec( $ch );
		
		if( curl_error($ch) ) { 
			throw new Exception("An error occured: ".curl_error($ch));
		}
		curl_close( $ch );
		
		return $response;
	}
}


?>