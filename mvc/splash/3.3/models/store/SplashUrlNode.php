<?php

/**
 * A SplashUrlNode is a datastructure optimised to navigate all possible URLs known to the application. 
 * A SplashUrlNode represents all possible routes starting at the current position (just after a / in a URL)
 * 
 * @author David Negrier
 */
class SplashUrlNode {
	/**
	 * An array of subnodes
	 * The key is the string to be appended to the URL
	 * 
	 * @var array<string, SplashUrlNode>
	 */
	private $children = array();
	
	/**
	 * An array of parameterized subnodes
	 * 
	 * @var array<string, SplashUrlNode>
	 */
	private $parameterizedChildren = array();
	
	/**
	 * A list of callbacks (assicated to there HTTP method).
	 * 
	 * @var array<string, SplashRoute>
	 */
	private $callbacks = array();
	
	public function registerCallback(SplashRoute $callback) {
		$this->addUrl(explode("/", $callback->url), $callback);
	}
	
	/**
	 * Registers a new URL.
	 * The URL is passed as an array of strings (exploded on /) 
	 * 
	 * @param array<string> $urlParts
	 */
	protected function addUrl(array $urlParts, SplashRoute $callback) {
		if (!empty($urlParts)) {
			$key = array_shift($urlParts);
			
			if (strpos($key, "{") === 0 && strpos($key, "}") === strlen($key)-1) {
				// Parameterized URL element
				$varName = substr($key, 1, strlen($key)-2);
				
				
				if (!isset($this->parameterizedChildren[$varName])) {
					$this->parameterizedChildren[$varName] = new SplashUrlNode();
				}
				$this->parameterizedChildren[$varName]->addUrl($urlParts, $callback);
			} else {
				// Usual URL element
				if (!isset($this->children[$key])) {
					$this->children[$key] = new SplashUrlNode();
				}
				$this->children[$key]->addUrl($urlParts, $callback);
			} 
		} else {
			if (empty($callback->httpMethods)) {
				if (isset($this->callbacks[""])) {
					throw new SplashException("An error occured while looking at the list URL managed in Splash. The URL '".$callback->url."' is associated "
						."to 2 methods: \$".$callback->controllerInstanceName."->".$callback->methodName." and \$".$this->callbacks[""]->controllerInstanceName."->".$this->callbacks[""]->methodName);
				}
				$this->callbacks[""] = $callback;
			} else {
				foreach ($callback->httpMethods as $httpMethod) {
					if (isset($this->callbacks[$httpMethod])) {
						throw new SplashException("An error occured while looking at the list URL managed in Splash. The URL '".$callback->url."' for HTTP method '".$httpMethod."' is associated "
							."to 2 methods: \$".$callback->controllerInstanceName."->".$callback->methodName." and \$".$this->callbacks[$httpMethod]->controllerInstanceName."->".$this->callbacks[$httpMethod]->methodName);
					}
					$this->callbacks[$httpMethod] = $callback;
				}
			}
			
		}
	}
	
	/**
	 * Walks through the nodes to find the callback associated to the URL
	 * 
	 * @param string $url
	 * @param string $httpMethod
	 * @return SplashRoute
	 */
	public function walk($url, $httpMethod) {
		return $this->walkArray(explode("/", $url), $httpMethod, array());
	}
	
	/**
	 * Walks through the nodes to find the callback associated to the URL
	 * 
	 * @param array $urlParts
	 * @param string $httpMethod
	 * @param SplashRequestContext $context
	 * @param array $parameters
	 * @return SplashRoute
	 */
	private function walkArray(array $urlParts, $httpMethod, array $parameters) {
		
		if (!empty($urlParts)) {
			$key = array_shift($urlParts);
			if (isset($this->children[$key])) {
				return $this->children[$key]->walkArray($urlParts, $httpMethod, $parameters);
			} else {
				foreach ($this->parameterizedChildren as $varName=>$splashUrlNode) {
					if (isset($parameters[$varName])) {
						throw new SplashException("An error occured while looking at the list URL managed in Splash. In a @URL annotation, the parameter '$parameter' appears twice. That should never happen");
					}
					$newParams = $parameters;
					$newParams[$varName] = $key;
					$result = $this->parameterizedChildren[$varName]->walkArray($urlParts, $httpMethod, $newParams);
					if ($result != null) {
						return $result;
					}
				}
				// If we arrive here, there was no parameterized URL matching our objective
				return null;
			}
		} else {
			if (isset($this->callbacks[$httpMethod])) {
				$route = $this->callbacks[$httpMethod];
				$route->filledParameters = $parameters;
				return $route;
			} elseif (isset($this->callbacks[""])) {
				$route = $this->callbacks[""];
				$route->filledParameters = $parameters;
				return $route;
			} else {
				return null;
			}
		}
		
	}
}