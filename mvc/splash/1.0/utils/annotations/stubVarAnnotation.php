<?php

/**
 * An annotation used to describe how parameters in a request are mapped to attributes in an action.
 * Syntax: @Var{param_name}(origin="request[req_name]/url[url_name]/session[session_name]")
 */
class stubVarAnnotation extends stubAbstractAnnotation implements stubAnnotation
{

	protected $origin;

	protected $validator;

    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_PARAM;
    }

    public function finish() {

    }

	public function setOrigin($origin) {
		$this->origin = $origin;
	}

	public function setValidator($validator) {
		$this->validator = $validator;
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
				throw new ValidatorException($command, $this->getParameterName(), $value);
			}

		}

		return $value;
	}

    private function getValueWithoutValidation() {
    	if ($this->origin === null) {
			return x_get($this->getParameterName());
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
						return x_get($param);
					} else {
						return x_get($this->getParameterName());
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
						$args = AdminBag::getInstance()->args;
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
    private function getParameterName() {
    	$annotationName = $this->getAnnotationName();
    	$posSharp = strpos($annotationName, "#");
		return trim(substr($annotationName, $posSharp+1));
    }

}

?>