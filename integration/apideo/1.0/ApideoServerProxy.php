<?php

/**
 * This class is used to query an Apideo video server about statistics, security settings, etc....
 *
 * @Component
 */
class ApideoServerProxy {
	
	/**
	 * The Apideo server url, should end with a slash.
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $videoServerURL = "http://video2.apideo.com/apideo_room3/";
	
	/**
	 * Uploads a new JS script that will be automatically triggered when a
	 * room is opened. 
	 * 
	 * @param string $apideoKey
	 * @param string $securityPhrase
	 * @param string $script
	 * @throws Exception
	 */
	public function uploadScript($apideoKey, $securityPhrase, $script) {
		$url = $this->videoServerURL."uploadScript.do";
		
		// Note: the adminsecurityphrase is stored directly in the program below, and in the Java code on the videoserver side.
		$params = array("apideokey"=>urlencode($apideoKey), "securityphrase"=>urlencode($securityPhrase), "script"=>urlencode($script));
		
		$fields_string = "";
		foreach($params as $key=>$value) { $fields_string  .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');

		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
		
		if( curl_error($ch) ) { 
			throw new ApideoServerProxyException("An error occured: ".curl_error($ch));
		} else {
			$response = curl_exec( $ch );
		}
		curl_close( $ch );
		
		
		$xmlRoot = simplexml_load_string($response);
		
		if ($xmlRoot == null) {
			throw new ApideoServerProxyException("An error occured while uploading script for Apideo key: ".$apideoKey.". Response: ".$response);
		}
		
		// If an error message is returned instead of a message
		if (!empty($xmlRoot->message)) {
			throw new ApideoServerProxyException("An error occured while uploading script for Apideo key: ".$apideoKey.". ".$xmlRoot->message." - ".$xmlRoot->stacktrace);
		}
	}
	
	/**
	 * Returns the currently running JS script.
	 *
	 * @param string $apideoKey
	 * @param string $securityPhrase
	 * @throws Exception
	 */
	public function getScript($apideoKey, $securityPhrase) {
		$url = $this->videoServerURL."getScript.do";
	
		// Note: the adminsecurityphrase is stored directly in the program below, and in the Java code on the videoserver side.
		$params = array("apideokey"=>urlencode($apideoKey), "securityphrase"=>urlencode($securityPhrase));
	
		$fields_string = "";
		foreach($params as $key=>$value) {
			$fields_string  .= $key.'='.$value.'&';
		}
		rtrim($fields_string,'&');
	
		// preparation de l'envoi
		$ch = curl_init();
	
		curl_setopt( $ch, CURLOPT_URL, $url);
	
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
	
		if( curl_error($ch) ) {
			throw new ApideoServerProxyException("An error occured: ".curl_error($ch));
		} else {
			$response = curl_exec( $ch );
		}
		curl_close( $ch );
	
	
		$xmlRoot = simplexml_load_string($response);
	
		if ($xmlRoot == null) {
			throw new ApideoServerProxyException("An error occured while retrieving server-side script for Apideo key: ".$apideoKey.". Response: ".$response);
		}
	
		// If an error message is returned instead of a message
		if (!empty($xmlRoot->message)) {
			throw new ApideoServerProxyException("An error occured while retrieving server-side script for Apideo key: ".$apideoKey.". ".$xmlRoot->message." - ".$xmlRoot->stacktrace);
		}
		
		return (string) $xmlRoot->serversidescript;
	}
	
	/**
	 * Executes a remote function, server-side, on Apideo servers.
	 * 
	 * 
	 * @param string $apideoKey
	 * @param string $securityPhrase
	 * @param string $roomName
	 * @param string $functionName
	 * Note: you can pass additional arguments, they will be passed to the server-side function.
	 */
	public function execRemoteFunction($apideoKey, $securityPhrase, $roomName, $functionName) {
		$url = $this->videoServerURL."execScript.do";
		
		// Note: the adminsecurityphrase is stored directly in the program below, and in the Java code on the videoserver side.
		$params = array("apideokey"=>$apideoKey, "securityphrase"=>$securityPhrase, "roomname"=>$roomName, "function"=>$functionName);
				
		$fields_string = "";
		foreach($params as $key=>$value) {
			$fields_string  .= $key.'='.urlencode($value).'&';
		}
		
		$functionArguments = func_get_args();
		// Let's remove the 4 first arguments of the function
		array_shift($functionArguments);
		array_shift($functionArguments);
		array_shift($functionArguments);
		array_shift($functionArguments);
		
		foreach($functionArguments as $value) {
			$fields_string  .= 'param[]='.urlencode($value).'&';
		}
		
		rtrim($fields_string,'&');
		
		// preparation de l'envoi
		$ch = curl_init();
		
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
		
		if( curl_error($ch) ) {
			throw new ApideoServerProxyException("An error occured while executing remote function '".$functionName."' for Apideo key: ".$apideoKey.", room '".$roomName."': ".curl_error($ch));
		}
		
		$response = curl_exec( $ch );
		if( curl_error($ch) ) {
			throw new ApideoServerProxyException("An error occured while executing remote function '".$functionName."' for Apideo key: ".$apideoKey.", room '".$roomName."': ".curl_error($ch));
		}
	
		curl_close( $ch );
		
		$jsonObj = json_decode($response);
		
		if (json_last_error() != JSON_ERROR_NONE) {
			throw new ApideoServerProxyException("An error occured while executing remote function '".$functionName."' for Apideo key: ".$apideoKey.", room '".$roomName."'. Response: ".$response);
		} 
		
		return $jsonObj;
	}
}
?>