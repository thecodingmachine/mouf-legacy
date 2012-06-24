<?php 
/**
 * Objects implementing this interface can be used to fetch the value to pass to a parameter in a method called by Splash.
 * 
 * @author David Negrier
 */
interface SplashParameterFetcherInterface {

	/**
	 * We pass the context of the request, the object returns the value to fill.
	 * 
	 * @param SplashRequestContext $context
	 * @return mixed
	 */
	public function fetchValue(SplashRequestContext $context);
}
?>