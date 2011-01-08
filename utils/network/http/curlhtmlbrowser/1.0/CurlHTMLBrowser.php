<?php

/**
 * Using the Curl HTML Browser, you can
 * easily query a web site in HTTP or HTTPS, and analyze the HTML answered. The Curl HTML Browser will handle sessions
 * for you, so you can use it to log into a website and retrieve data from the logged part of the website.
 * 
 * @Component
 */
class CurlHTMLBrowser {
	
	/**
	 * The file where cookies will be stored.
	 * If empty, the CurlHTMLBrowser will create that file in the default temp directory.
	 * 
	 * @Property
	 * @var string
	 */
	public $cookieFile;
	
	/**
	 * Performs a query on the URL $url, using a "get" or "post" method, passing
	 * in parameter the $params array.
	 * 
	 * @param string $url Should start with http:// or https://
	 * @param string $method the HTTP "get" or "post" method.
	 * @param array $params
	 */
	public function query($url, $method="get", $params=null) {
		if ($this->cookieFile == null) {
			$cookieJar = tempnam(sys_get_temp_dir(), "cookiejar");
		} else {
			$cookieJar = $this->cookieFile;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookieJar);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
		curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
		
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		
		$err = curl_error($ch);
		if ($err) {
			throw new CurlHTMLBrowserException($err);
		}
		
		curl_close($ch);
		
		return new CurlHTMLPage($output, $info);
	}
}