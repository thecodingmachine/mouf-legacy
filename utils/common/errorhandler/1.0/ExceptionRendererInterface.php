<?php

/**
 * Objects implementing this interface can render exceptions.
 * 
 * @author David Negrier
 */
interface ExceptionRendererInterface {
	
	/**
	 * Renders the exception and returns the text for this rendered exception.
	 * 
	 * @param Exception $exception
	 * @return string
	 */
	public function renderException(Exception $exception);
}