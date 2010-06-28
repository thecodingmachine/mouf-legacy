<?php

/**
 * An annotation used to describe how parameters in a request are mapped to attributes in an action.
 * Syntax: @param int $myvar (origin="request[req_name]/url[url_name]/session[session_name]",validator="MyValidator") Some comments
 */
class paramAnnotation //extends stubAbstractAnnotation implements stubAnnotation
{
	
	// The complete string after @param
	private $completeString;
	
	/**
	 * The type the param must match. Can be int, string, etc...
	 *
	 * @var string
	 */
	private $type;
	
	/**
	 * The variable the annotation applies to.
	 *
	 * @var string
	 */
	private $var;
	
	public function __construct($value) {
		$this->completeString = $value;
		// Let's get the 2 first words:
		$tmpStr = trim($this->completeString);
		
		$this->type = strtok($tmpStr, " \n\t");
		
		if ($this->type === false) {
			throw new Exception('Error while reading the @param annotation. At least, an annotation must be in the form: "@param type $var". But the annotation encountered is: @param '.$value);
		}
		
		$this->var = strtok(" \n\t");
		
		
		if ($this->var === false) {
			throw new Exception('Error while reading the @param annotation. At least, an annotation must be in the form: "@param type $var". But the annotation encountered is: @param '.$value);
		}
		
		if (strpos($this->var,'$')!==0) {
			// If the variable does not start with $, there is a problem.
			throw new Exception('Error while reading the @param annotation. At least, an annotation must be in the form: "@param type $var". But the annotation encountered is: @param '.$value."\nMaybe you forgot the leading \$ sign in front of the variable name.");
		}
		$this->var = substr($this->var, 1);

		$additionalParams = strtok('');

		if (strpos($additionalParams, "(") === 0) {
			// Ok, there are additional parameters if we start with a (.
			$pos = strpos($additionalParams, ")");
			if ($pos === false) {
				throw new Exception('Error while reading the @param annotation. Could not find the closing parenthesis. But the annotation encountered is: @param '.$value);
			}
			$additionalParams = substr($additionalParams, 1, $pos-1);
			$splitParamsStrings = explode(",", $additionalParams);
			$splitParamsArray = array();
			foreach ($splitParamsStrings as $string) {
				//$splitParamsStringsTrim[] = trim($string);
				$equalsArr = explode('=', $string);
				if (count($equalsArr) != 2) {
					throw new Exception('Error while reading the @param annotation. Wrong syntax: @param '.$value);
				}
				$splitParamsArray[trim($equalsArr[0])] = trim($equalsArr[1]);
			}

			if (isset($splitParamsArray['origin'])) {
				$this->origin = $splitParamsArray['origin'];
			}
			if (isset($splitParamsArray['validator'])) {
				$this->validator = $splitParamsArray['validator'];
			}
			
		}
		
	}

	protected $origin;

	protected $validator;

    /*public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_PARAM;
    }*/

    public function finish() {

    }

	public function setOrigin($origin) {
		$this->origin = $origin;
	}

	public function setValidator($validator) {
		$this->validator = $validator;
	}

	/**
	 * The variable name the annotation applies to.
	 *
	 * @return string
	 */
	public function getVariableName() {
		return $this->var;
	}
	
	/**
	 * The variable type the annotation applies to.
	 *
	 * @return string
	 */
	public function getVariableType() {
		return $this->type;
	}
	
	/**
	 * Returns the value for this attribute based on the origin string.
	 * The validators are applied to that value.
	 */
	public function getValue() {
		$value = $this->getValueWithoutValidation();

		$validators = explode("/", $this->validator);

		foreach ($validators as $myValidator) {
			$posBracket = strpos($myValidator, "[");
			if ($posBracket === false) {
				$command = trim($myValidator);
				$param = null;
			} else {
				$command = trim(substr($myValidator, 0, $posBracket));
				$paramTemp =  substr($myValidator, $posBracket+1);
				$posCloseBracket = strpos($paramTemp, "]");
				if ($posCloseBracket === false) {
					$exception = new AnnotationException();
					$exception->setTitle('controller.annotation.var.missingclosebracket.title');
					$exception->setMessage('controller.annotation.var.missingclosebracket.text', $this->validator);
					throw $exception;
				}
				$param =  trim(substr($paramTemp, 0, $posCloseBracket));
			}

			if (empty($command))
				continue;

			// Ok, let's try to find the validators:
			$validatorClass = $command."Validator";
			if (!class_exists($validatorClass)) {
				$exception = new AnnotationException();
				$exception->setTitle('controller.annotation.var.unabletofindvalidator.title');
				$exception->setMessage('controller.annotation.var.unabletofindvalidator.text', $validatorClass);
				throw $exception;
			}

			$validator = new $validatorClass($param);

			$validates = $validator->validate($value);
			if (!$validates) {
				// TODO: provide specialized behaviour in case of validation failure!
				//throw new ValidatorException($command, $this->getParameterName(), $value);
				$exception = new AnnotationException();
				$exception->setTitle('controller.annotation.var.validation.error.title');
				$exception->setMessage("controller.annotation.var.validation.error", $command, $this->getParameterName(), $value);
				
				throw $exception;
			}

		}

		return $value;
	}

    private function getValueWithoutValidation() {
    	if ($this->type == null) {
    		$type = "string";
    	} else {
    		$type = $this->type;
    	}
    	if ($this->origin === null) {
   			return get($this->getParameterName(), $type, false, null);
    	}

		$origins = explode("/", $this->origin);

		foreach ($origins as $myOrigin) {
			$posBracket = strpos($myOrigin, "[");
			if ($posBracket === false) {
				$command = trim($myOrigin);
				$param = null;
			} else {
				$command = trim(substr($myOrigin, 0, $posBracket));
				$paramTemp =  substr($myOrigin, $posBracket+1);
				$posCloseBracket = strpos($paramTemp, "]");
				if ($posCloseBracket === false) {
					$exception = new AnnotationException();
					$exception->setTitle('controller.annotation.var.missingclosebracket.title');
					$exception->setMessage('controller.annotation.var.missingclosebracket.text', $this->origin);
					throw $exception;
				}
				$param =  trim(substr($paramTemp, 0, $posCloseBracket));
			}

			switch ($command) {
				case "request":
					if ($param != null) {
						return get($param, $type);
					} else {
						return get($this->getParameterName(), $type);
					}
					break;
				case "session":
					if ($param != null) {
						return $_SESSION[$param];
					} else {
						return $_SESSION[$this->getParameterName()];
					}
					break;
				case "url":
					if ($param == null) {
						$exception = new AnnotationException();
						$exception->setTitle('controller.annotation.var.urlorigintakesanint.title');
						$exception->setMessage('controller.annotation.var.urlorigintakesanint.text', $this->origin);
						throw $exception;
					} else {
						if (!is_numeric($param)) {
							$exception = new AnnotationException();
							$exception->setTitle('controller.annotation.var.urlorigintakesanint.title');
							$exception->setMessage('controller.annotation.var.urlorigintakesanint.text', $this->origin);
							throw $exception;
						}
						$args = $this->getArgs();
						
						if (isset($args["arg".$param]))
							return $args["arg".$param];
					}
					break;
				default:
					$exception = new AnnotationException();
					$exception->setTitle('controller.annotation.var.incorrectcommand.title');
					$exception->setMessage('controller.annotation.var.incorrectcommand.text', $command);
					throw $exception;
			}

		}

		// Nothing found? Let's return null.
		return null;
    }

    /**
     * Returns the name of the parameter.
     * @return string The name of the parameter.
     */
    public function getParameterName() {
    	return $this->var;
    }

    private function getArgs() {
		$redirect_uri = $_SERVER['REDIRECT_URL'];
		$pos = strpos($redirect_uri, ROOT_URL);
		$action = substr($redirect_uri, $pos+strlen(ROOT_URL));

		$array = explode("/", $action);
		$args = array();

		array_shift($array);
		array_shift($array);

		$i=0;
		foreach ($array as $arg) {
			$args["arg$i"]=$arg;
			$i++;
		}
		return $args;
	}
	    
}

?>