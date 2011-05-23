<?php


/**
 * This class is a utility class used to perform requests on files in Mouf. This is usually done from the Mouf admin, to query the Mouf application.
 *
 */
class MoufProxy {

	/**
	 * Returns a proxy instance of an object.
	 * Any function call to the instance will be executed in a separate process.
	 * This is very useful to call methods on objects living in the "application" scope when you are in the "admin" scope.
	 * 
	 * For instance:
	 * <pre>
	 * 	$myProxyObject = MoufProxy('myInstance');
	 * 	$result = $myProxyObject->myMethod();
	 * </pre>
	 * 
	 * Warning! Each function call is executed in a different process.
	 * This is slow, and context is not kept. This means using setters or getters is mostly useless.
	 * 
	 * @param string $name
	 * @param bool $selfEdit
	 */
	public static function getInstance($name, $selfEdit = false) {
		return new MoufProxyInstance($name, $selfEdit);
	}
	
	/**
	 * Performs a request to a Mouf PHP file.
	 * The request is performed in HTTP, using CURL.
	 * The request URL must be relative to the ROOT_URL, with no starting /. 
	 * 
	 * The result of the request should be a PHP serialized object.
	 * 
	 * @param string $url
	 * @param array $parameters
	 */
	public static function request($url, $parameters = array()) {
		
		$response = self::performRequest(MoufReflectionProxy::getLocalUrlToProject().$url, $parameters);
		
		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		return $obj;
	}
	
	/**
	 * Performs a request using CURL and returns the result.
	 * 
	 * @param string $url
	 * @throws Exception
	 */
	private static function performRequest($url, $post = array()) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($post) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		} else
			curl_setopt($ch, CURLOPT_POST, false);
		$response = curl_exec($ch );
		
		if( curl_error($ch) ) { 
			throw new Exception("An error occured: ".curl_error($ch));
		}
		curl_close( $ch );
		
		return $response;
	}
	
}