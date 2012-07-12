<?php

/**
 * 
 * @author David Négrier
 * @Component
 */
class ErrorTextRenderer implements ErrorRendererInterface, ExceptionRendererInterface {
	
	/**
	 * Renders the error and returns the text for this rendered error.
	 *
	 * @param Error $error
	 * @return string
	 */
	public function renderError(PhpError $error) {
		$textTrace = $error->getLevelAsString().": ".$error->getStr()."\n";
		$textTrace .= "File: ".$error->getFile()."(Line: ".$error->getLine().")\n";
		$textTrace .= "Stacktrace:\n";
		$textTrace .= self::getTextBackTrace($error->getDebugTrace());
		
		// TODO: add context
		
		return $textTrace;
	}
	
	
	/**
	 * Renders the exception and returns the text for this rendered exception.
	 *
	 * @param Exception $exception
	 * @return string
	 */
	public function renderException(Exception $exception) {
		$textTrace = "Message: ".$exception->getMessage()."\n";
		$textTrace .= "File: ".$exception->getFile()."\n";
		$textTrace .= "Line: ".$exception->getLine()."\n";
		$textTrace .= "Stacktrace:\n";
		$textTrace .= self::getTextBackTrace($exception->getTrace());
		return $textTrace;
	}
		
	/**
	 * Returns the Exception Backtrace as a text string.
	 *
	 * @param unknown_type $backtrace
	 * @return unknown
	 */
	static private function getTextBackTrace($backtrace) {
		$str = '';
	
		foreach ($backtrace as $step) {
			if ($step['function']!='getTextBackTrace' && $step['function']!='handle_error')
			{
				if (isset($step['file']) && isset($step['line'])) {
					$str .= "In ".$step['file'] . " at line ".$step['line'].": ";
				}
				if (isset($step['class']) && isset($step['type']) && isset($step['function'])) {
					$str .= $step['class'].$step['type'].$step['function'].'(';
				}
	
				if (isset($step['args']) && is_array($step['args'])) {
					$drawn = false;
					$params = '';
					foreach ( $step['args'] as $param)
					{
						$params .= self::getPhpVariableAsText($param);
						//$params .= var_export($param, true);
						$params .= ', ';
						$drawn = true;
					}
					$str .= $params;
					if ($drawn == true)
					$str = substr($str, 0, strlen($str)-2);
				}
				$str .= ')';
				$str .= "\n";
			}
		}
	
		return $str;
	}
	
	/**
	 * Used by the debug function to display a nice view of the parameters.
	 *
	 * @param mixed $var
	 * @return string
	 */
	public static function getPhpVariableAsText($var, $depth = 0) {
		if( is_string( $var ) )
		return( '"'.str_replace( array("\x00", "\x0a", "\x0d", "\x1a", "\x09"), array('\0', '\n', '\r', '\Z', '\t'), $var ).'"' );
		else if( is_int( $var ) || is_float( $var ) )
		{
			return( $var );
		}
		else if( is_bool( $var ) )
		{
			if( $var )
			return( 'true' );
			else
			return( 'false' );
		}
		else if( is_array( $var ) )
		{
			$result = 'array( ';
			$depth++;
			if ($depth < 2) {
				$comma = '';
				foreach( $var as $key => $val )
				{
					$result .= $comma.self::getPhpVariableAsText( $key ).' => '.self::getPhpVariableAsText( $val, $depth );
					$comma = ', ';
				}
			} else {
				$result .= "skipped";
			}
			$result .= ' )';
			return( $result );
		}

		elseif (is_object($var)) return "Object ".get_class($var);
		elseif(is_resource($var)) return "Resource ".get_resource_type($var);
		return "Unknown type variable";
	}
}