<?php 
/**
 * This class fetches the parameter from the path of the URL.
 * 
 * @author David Negrier
 */
class SplashUrlParameterFetcher implements SplashParameterFetcherInterface {

	private $key;
	
	/**
	 * Constructor
	 * @param string $key The name of the parameter to fetch.
	 */
	public function __construct($key) {
		$this->key = $key;
	}
	
	/**
	 * We pass the context of the request, the object returns the value to fill.
	 * 
	 * @param SplashRequestContext $context
	 * @return mixed
	 */
	public function fetchValue(SplashRequestContext $context) {
		$request = $context->getUrlParameters();
		return $request[$this->key];
	}
}
?>