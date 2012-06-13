<?php

/**
 * 
 * @author David Négrier
 * @Component
 */
class ErrorHtmlRenderer implements ErrorRendererInterface {
	
	/**
	 * Krumo can be used to render the value of variables inside the stacktrace.
	 * It can be a valuable tool, but relies heavily on CSS and Javascript.
	 * So you might want to avoid using it if you send the error via email for instance.
	 * 
	 * @Property
	 * @var bool
	 */
	public $useKrumo = true;
	
	/**
	 * Renders the error and returns the text for this rendered error.
	 *
	 * @param Error $error
	 * @return string
	 */
	public function renderError(PhpError $error) {
		$level = $error->getLevel();
		$msg='';
		
		$msg = '<table>';
		//$str .= '<tr><td>Method</td></tr>';
		//$str .= '<tr><td>Method</td><td>File</td><td>Line</td></tr>';
		
		switch ($level) {
			case E_WARNING:
				$color = "#FFAA00";
				$type = "Warning";
				break;
			case E_NOTICE:
				$color = "#009999";
				$type = "Notice";
				break;
			case E_STRICT:
				$color = "#FFFF00";
				$type = "PHP5 Not Strict";
				break;
			case E_USER_ERROR:
				$color = "#FF0000";
				$type = "User Error";
				break;
			case E_USER_WARNING:
				$color = "#FFFF00";
				$type = "User Warning";
				break;					
			case E_USER_NOTICE:
				$color = "#009999";
				$type = "User Notice";
				break;
			case E_RECOVERABLE_ERROR:
				$color = "#FF3333";
				$type = "Recoverable Error";
				break;
			default:
				$color = "#000000";
				$type = "Unknown Error level";
		}
		
		$msg .= "<tr><td colspan='3' style='background-color:$color; color:white; text-align:center'><b>$type</b></td></tr>";
		
		$msg .= "<tr><td style='background-color:#AAAAAA; color:white; text-align:center'>Context/Message</td>";
		$msg .= "<td style='background-color:#AAAAAA; color:white; text-align:center'>File</td>";
		$msg .= "<td style='background-color:#AAAAAA; color:white; text-align:center'>Line</td></tr>";
		
		$msg .= "<tr><td style='background-color:#EEEEEE; color:black'><b>".$error->getStr()."</b></td>";
		$msg .= "<td style='background-color:#EEEEEE; color:black'>".htmlspecialchars(self::shortenFilePath($error->getFile()), ENT_NOQUOTES, "UTF-8")."</td>";
		$msg .= "<td style='background-color:#EEEEEE; color:black'>".$error->getLine()."</td></tr>";
		$msg .= $this->getHTMLBackTrace($error->getDebugTrace());
		//krumo::backtrace();
		$msg .= "</table>";
		
		return $msg;
	}
	
	/**
	 * Renders the exception and returns the text for this rendered exception.
	 *
	 * @param Exception $exception
	 * @return string
	 */
	public function renderException(Exception $exception) {
		$msg='';
		
		$msg = '<table>';
		
		
		$display_errors = ini_get('display_errors');
		$color = "#FF0000";
		$type = "Uncaught ".get_class($exception);
		if ($exception->getCode() != null)
		$type.=" with error code ".$exception->getCode();
		
		$msg .= "<tr><td colspan='3' style='background-color:$color; color:white; text-align:center'><b>$type</b></td></tr>";
		
		$msg .= "<tr><td style='background-color:#AAAAAA; color:white; text-align:center'>Context/Message</td>";
		$msg .= "<td style='background-color:#AAAAAA; color:white; text-align:center'>File</td>";
		$msg .= "<td style='background-color:#AAAAAA; color:white; text-align:center'>Line</td></tr>";
		
		$msg .= "<tr><td style='background-color:#EEEEEE; color:black'><b>".nl2br($exception->getMessage())."</b></td>";
		$msg .= "<td style='background-color:#EEEEEE; color:black'>".self::shortenFilePath($exception->getFile())."</td>";
		$msg .= "<td style='background-color:#EEEEEE; color:black'>".$exception->getLine()."</td></tr>";
		$msg .= $this->getHTMLBackTrace($exception->getTrace());
		$msg .= "</table>";
		
		return $msg;
	}
	
	
	/**
	 * Returns the Exception Backtrace as a nice HTML view.
	 *
	 * @param unknown_type $backtrace
	 * @return unknown
	 */
	private function getHTMLBackTrace($backtrace) {
		$str = '';
	
		foreach ($backtrace as $step) {
			if ($step['function']!='getHTMLBackTrace' && $step['function']!='handle_error')
			{
				$str .= '<tr><td style="border-bottom: 1px solid #EEEEEE">';
				$str .= ((isset($step['class']))?htmlspecialchars($step['class'], ENT_NOQUOTES, "UTF-8"):'').
				((isset($step['type']))?htmlspecialchars($step['type'], ENT_NOQUOTES, "UTF-8"):'').htmlspecialchars($step['function'], ENT_NOQUOTES, "UTF-8").'(';
	
				if (isset($step['args']) && is_array($step['args'])) {
					$drawn = false;
					$params = '';
					
					if ($this->useKrumo) {
					static $cnt = 0;
						$cnt++;
						$str .= "<span style='cursor:pointer' id='hidden_params_list_$cnt' onclick='document.getElementById(\"hidden_params_list_$cnt\").style.display = \"none\";document.getElementById(\"displayed_params_list_$cnt\").style.display = \"block\";'>...)";
						
						$str .= "</span>";
						
						foreach ( $step['args'] as $param)
						{
							//$params .= ErrorTextRenderer::getPhpVariableAsText($param);
							ob_start();
							krumo($param);
							$params .= ob_get_clean();
							//$params .= var_export($param, true);
							//$params .= ', ';
							$drawn = true;
						}
						//$str .= htmlspecialchars($params, ENT_NOQUOTES, "UTF-8");
		
						$str .= "<div id='displayed_params_list_$cnt' style='display:none'>";
						$str .= $params;
						$str .= "</div>";
					} else {
						$str .= "...";
					}
					
					if ($drawn == true)
					$str = substr($str, 0, strlen($str)-2);
				}
				$str .= ')';
				$str .= '</td><td style="border-bottom: 1px solid #EEEEEE">';
				if (isset($step['file'])) {
					$file = self::shortenFilePath($step['file']);
					$str .= htmlspecialchars($file, ENT_NOQUOTES, "UTF-8");
				}
				$str .= '</td><td style="border-bottom: 1px solid #EEEEEE">';
				$str .= ((isset($step['line']))?$step['line']:'');
				$str .= '</td></tr>';
			}
		}
	
		return $str;
	}
	
	/**
	 * Removes the ROOT_PATH from the path passed in parameter.
	 * 
	 * @param string $filePath
	 * @return string
	 */
	private static function shortenFilePath($filePath) {
		if (strpos($filePath, ROOT_PATH) === 0) {
			$filePath = substr($filePath, strlen(ROOT_PATH));
		}
		return $filePath;
	}
}