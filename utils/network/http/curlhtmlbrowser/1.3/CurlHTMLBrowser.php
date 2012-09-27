<?php
define('CURL_HTTP_URL_REPLACE', 1);				// Replace every part of the first URL when there's one of the second URL
define('CURL_HTTP_URL_JOIN_PATH', 2);			// Join relative paths
define('CURL_HTTP_URL_JOIN_QUERY', 4);			// Join query strings
define('CURL_HTTP_URL_STRIP_USER', 8);			// Strip any user authentication information
define('CURL_HTTP_URL_STRIP_PASS', 16);			// Strip any password authentication information
define('CURL_HTTP_URL_STRIP_AUTH', 32);			// Strip any authentication information
define('CURL_HTTP_URL_STRIP_PORT', 64);			// Strip explicit port numbers
define('CURL_HTTP_URL_STRIP_PATH', 128);			// Strip complete path
define('CURL_HTTP_URL_STRIP_QUERY', 256);		// Strip query string
define('CURL_HTTP_URL_STRIP_FRAGMENT', 512);		// Strip any fragments (#identifier)
define('CURL_HTTP_URL_STRIP_ALL', 1024);			// Strip anything but scheme and host

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
	 * The real file that will be used for storing cookies (contains the autogenerated
	 * file name id cookieFile is empty).
	 * @var string
	 */
	private $cookieJar;
	
	/**
	 * The last URL that was accessed.
	 * 
	 * @var string
	 */
	private $lastUrl;
	
	/**
	 * Performs a query on the URL $url, using a "get" or "post" method, passing
	 * in parameter the $params array.
	 * The first URL passed in parameter should start with http:// or https://.
	 * Subsequent calls can be provided as a relative URL, or even "null", if you
	 * want to query the last page loaded.
	 * 
	 * @param string $url 
	 * @param string $method the HTTP "get" or "post" method.
	 * @param array $params
	 * @param array $headers
	 */
	public function query($url, $method="get", $params=null, $headers=null, $cookies=null /*, $javascript_loop=0*/) {
		//echo "Browsing page ".$url."<br/>";
		$method = strtolower($method);
		if ($method != "post" && $method != "get") {
			throw new CurlHTMLBrowserException("The HTTP method passed in parameter must be 'get' or 'post'.");
		}
		
		// Let's see if this is a relative URL. If so, let's try to correct things.
		if ($this->lastUrl != null) {
			$parsedUrl = parse_url($url);
			if (empty($parsedUrl['host'])) {
				$url = $this->http_build_url($this->lastUrl, $parsedUrl);
			}
		}
		
		if ($this->cookieFile == null) {
			if ($this->cookieJar == null) {
				$this->cookieJar = tempnam(sys_get_temp_dir(), "cookiejar");
			}
		} else {
			$this->cookieJar = $this->cookieFile;
		}

		$paramTmpArray = array();
		if ($params) {
			foreach ($params as $key=>$value) {
				$paramTmpArray[] = urlencode($key)."=".urlencode($value);
			}
			$paramStr = implode("&", $paramTmpArray);
		} else {
			$paramStr = "";
		}
		
		$ch = curl_init();
		
		if ($method == "get" && $params) {
			if (strpos($url, "?") === false) {
				$url .= "?";
			} else {
				$url .= "&";
			}
			$url .= $paramStr;
		}
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		if($headers)
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		// Set cookie values
		if($cookies)
			curl_setopt($ch, CURLOPT_COOKIE, $cookies);
			
		//array("Content-Type: application/json; charset=utf-8","Accept:application/json, text/javascript, */*; q=0.01")
		
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	    curl_setopt( $ch, CURLOPT_ENCODING, "" );
	    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	    //curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	    //curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	    
		
		if ($method == "post") {
			curl_setopt ($ch, CURLOPT_POST, 1);
 			curl_setopt ($ch, CURLOPT_POSTFIELDS, $paramStr);
		}
		
		curl_setopt($ch, CURLOPT_COOKIEJAR,  $this->cookieJar);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieJar);
		curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
		
		$output = curl_exec($ch);
		$response = curl_getinfo($ch);
		
		$err = curl_error($ch);
		if ($err) {
			throw new CurlHTMLBrowserException($err);
		}
		
		curl_close($ch);
		
		if ($response['http_code'] == 404) {
			throw new CurlHTMLBrowser404Exception("Error, the page '".$url."' access failed with a 404 Page not found error.");
		}
		if ($response['http_code'] == 500) {
			throw new CurlHTMLBrowser404Exception("Error, the page '".$url."' access failed with a 500 Server error message.");
		}
		if (strpos($response['http_code'], "4") === 0 || strpos($response['http_code'], "5") === 0) {
			throw new CurlHTMLBrowserException("Error, the page '".$url."' access failed with a ".$response['http_code']." error code.");
		}
		
		if ($response['http_code'] == 301 || $response['http_code'] == 302)
	    {
	        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	
	        if ( $headers = get_headers($response['url']) )
	        {
	            foreach( $headers as $value )
	            {
	                if ( substr( strtolower($value), 0, 9 ) == "location:" )
	                    return $this->query( trim( substr( $value, 9, strlen($value) ) ) );
	            }
	        }
	    }
	
	    /*if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $output, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $output, $value) ))
	    {
	    	if ($javascript_loop < 5) {
	        	return $this->query( $value[1], $javascript_loop+1 );
	    	} else {
	    		throw new CurlHTMLBrowserException("5 Javascript redirects in a row. It is likely CurlHTMLBrowser is not smart enough to handle this type of requests.");
	    	}
	    }*/
	    
		$this->lastUrl = $url;
		
		return new CurlHTMLPage($output, $response, $url);
	}
	
	
	// http_build_url function thanks to tycoonmaster: http://www.php.net/manual/fr/function.http-build-url.php
	// Build an URL
	// The parts of the second URL will be merged into the first according to the flags argument. 
	// 
	// @param	mixed			(Part(s) of) an URL in form of a string or associative array like parse_url() returns
	// @param	mixed			Same as the first argument
	// @param	int				A bitmask of binary or'ed HTTP_URL constants (Optional)CURL_HTTP_URL_REPLACE is the default
	// @param	array			If set, it will be filled with the parts of the composed url like parse_url() would return 
	private function http_build_url($url, $parts=array(), $flags=CURL_HTTP_URL_REPLACE, &$new_url=false)
	{
		$keys = array('user','pass','port','path','query','fragment');
		
		// CURL_HTTP_URL_STRIP_ALL becomes all the CURL_HTTP_URL_STRIP_Xs
		if ($flags & CURL_HTTP_URL_STRIP_ALL)
		{
			$flags |= CURL_HTTP_URL_STRIP_USER;
			$flags |= CURL_HTTP_URL_STRIP_PASS;
			$flags |= CURL_HTTP_URL_STRIP_PORT;
			$flags |= CURL_HTTP_URL_STRIP_PATH;
			$flags |= CURL_HTTP_URL_STRIP_QUERY;
			$flags |= CURL_HTTP_URL_STRIP_FRAGMENT;
		}
		// CURL_HTTP_URL_STRIP_AUTH becomes CURL_HTTP_URL_STRIP_USER and CURL_HTTP_URL_STRIP_PASS
		else if ($flags & CURL_HTTP_URL_STRIP_AUTH)
		{
			$flags |= CURL_HTTP_URL_STRIP_USER;
			$flags |= CURL_HTTP_URL_STRIP_PASS;
		}
		
		// Parse the original URL
		$parse_url = parse_url($url);
		
		// Scheme and Host are always replaced
		if (isset($parts['scheme']))
			$parse_url['scheme'] = $parts['scheme'];
		if (isset($parts['host']))
			$parse_url['host'] = $parts['host'];
		
		// (If applicable) Replace the original URL with it's new parts
		if ($flags & CURL_HTTP_URL_REPLACE)
		{
			foreach ($keys as $key)
			{
				if (isset($parts[$key]))
					$parse_url[$key] = $parts[$key];
			}
		}
		else
		{
			// Join the original URL path with the new path
			if (isset($parts['path']) && ($flags & CURL_HTTP_URL_JOIN_PATH))
			{
				if (isset($parse_url['path']))
					$parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
				else
					$parse_url['path'] = $parts['path'];
			}
			
			// Join the original query string with the new query string
			if (isset($parts['query']) && ($flags & CURL_HTTP_URL_JOIN_QUERY))
			{
				if (isset($parse_url['query']))
					$parse_url['query'] .= '&' . $parts['query'];
				else
					$parse_url['query'] = $parts['query'];
			}
		}
			
		// Strips all the applicable sections of the URL
		// Note: Scheme and Host are never stripped
		foreach ($keys as $key)
		{
			if ($flags & (int)constant('CURL_HTTP_URL_STRIP_' . strtoupper($key)))
				unset($parse_url[$key]);
		}
		
		
		$new_url = $parse_url;
		
		return 
			 ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
			.((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') .'@' : '')
			.((isset($parse_url['host'])) ? $parse_url['host'] : '')
			.((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
			.((isset($parse_url['path'])) ? $parse_url['path'] : '')
			.((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
			.((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '')
		;
	}
	
	function __destruct() {
		//To prevent the storage of too much file
		unlink($this->cookieJar);	
	}
}