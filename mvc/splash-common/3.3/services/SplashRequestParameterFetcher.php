<?php 
/**
 * This class fetches the parameter from the request.
 * 
 * @author David Negrier
 */
class SplashRequestParameterFetcher implements SplashParameterFetcherInterface {

	private $key;
	
	/**
	 * Whether the parameter is compulsory or not.
	 * 
	 * @var bool
	 */
	private $compulsory;
	
	/**
	 * The default value for the parameter.
	 * 
	 * @var mixed
	 */
	private $default;
	
	/**
	 * Constructor
	 * @param string $key The name of the parameter to fetch.
	 */
	public function __construct($key, $compulsory = true, $default = null) {
		$this->key = $key;
		$this->compulsory = $compulsory;
		$this->default = $default;
	}
	
	/**
	 * We pass the context of the request, the object returns the value to fill.
	 * 
	 * @param SplashRequestContext $context
	 * @return mixed
	 */
	public function fetchValue(SplashRequestContext $context) {
		$request = $context->getRequestParameters();
		if (isset($request[$this->key])) {
			return $request[$this->key];
		} elseif (!$this->compulsory) {
			return $this->default;
		} else {
			throw new SplashMissingParameterException($this->key);
		}
	}
}
?>