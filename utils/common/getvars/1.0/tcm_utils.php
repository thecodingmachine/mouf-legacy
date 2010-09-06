<?php
/**
 * String functions
 *                          DB protected
 *                               ^
 *                               |
 *                               v
 *    UserInput <----------> Plain String <----------------> HTML Output
 *                               ^
 *                               |
 *                               v
 *                          HTML Protected
 *
 * @version   $Id: xaja_tcm_utils.php,v 1.12 2006/05/24 15:20:34 dnegrier Exp $
 */

/**
 * userinput_to_plainstring() - Convert userinput to plain string.
 *
 * @param		string	The string to convert
 */
function userinput_to_plainstring($str) {
	if (get_magic_quotes_gpc()==1)
	{
		$str = stripslashes($str);
		// Rajouter les slashes soumis par l'utilisateur
		//$str = str_replace('\\', '\\\\', $str);
		return $str;
	}
	else
		return $str;
}

function plainstring_to_htmlprotected($str)
{
	return htmlspecialchars($str, ENT_QUOTES);
}

function plainstring_to_dbprotected($str)
{
	return addcslashes($str, "'\\");
}

function plainstring_to_htmloutput($str)
{
	return nl2br($str);
}

function plainstring_to_urlprotected($str)
{
	return urlencode($str);
}

function userinput_to_htmlprotected($str)
{
	return plainstring_to_htmlprotected(userinput_to_plainstring($str));
}

function dbdate_to_displaydate($strDate, $ccode="en", $long=false)
{
	if (!$strDate) return null;
	if ($ccode=="fr" && $long==false){
		return date("d M Y",strtotime($strDate));
	}
	else if ($ccode=="fr" && $long==true){
		return date("d F Y",strtotime($strDate));
	}
	else if ($ccode=="en" && $long==true)
		return date("F dS, Y",strtotime($strDate));
	else{
		$date = date("M dS, Y",strtotime($strDate));
		return $date;
	}
}

/**
 * Returns the content of a REQUEST or SESSION or COOKIE parameter.
 * This function does automatically remove any added \
 * 
 *
 * @param string $var The name of the Request or Session parameter.
 * @param string $type Can be "string", "int", "float", "date", "array" or "unknown_type"
 * @param bool $compulsory Can be true or false
 * @param unknown_type $default_value Default value of not compulsory
 * @param string $origin A string containing R and S or C. R for Request, S for Session, C for Cookie. So this can be "R","S","RSC" or whatever. The first found will stop the search.
 */
function get($var, $type="unknown_type", $compulsory=false, $default_value=false, $origin="R") {
	for ($i=0; $i<strlen($origin); $i++)
	{
		if ($origin{$i} == 'R' || $origin{$i} == 'r')
		{
			// get de variables classiques
			if (isset($_REQUEST[$var]))
			{
				// we check first for arrays
				if (is_array($_REQUEST[$var])){
					if ($type != "unknown_type" && $type != "array") {
						echo '<b>Error!</b> The '.$var.' field must be a '.$type.". Array passed instead.";
						exit;
					}
					$array = $_REQUEST[$var];
					if (!array_walk_recursive($array, "userinput_to_plainstring")) {
						echo '<b>Error!</b> An error occured while walking array '.$var.'.';
						exit;
					}
					
					return $array;
				}
				
				if ($type=='string' || $type=='unknown_type')
					return userinput_to_plainstring($_REQUEST[$var]);
				elseif ($type=='int')
				{
					if (!check_integer($_REQUEST[$var]))
					{
						echo '<b>Error!</b> The '.$var.' field must be an integer';
						exit;
					}
					return $_REQUEST[$var];
				}
				elseif ($type=='float')
				{
					if (!is_numeric($_REQUEST[$var]))
					{
						echo '<b>Error!</b> The '.$var.' field must be a number';
						exit;
					}
					return $_REQUEST[$var];
				}
				elseif ($type=='date')
				{
					$date = strtotime($_REQUEST[$var]);
					if (!$date || $date == -1)
					{
						echo '<b>Error!</b> The '.$var.' field must be a date';
						exit;
					}
					return $_REQUEST[$var];
				}
				elseif ($type=='array')
				{
					echo '<b>Error!</b> The '.$var.' field must be an array';
					exit;
				}
				else 
				{
					echo '<b>Error!</b> Unknown required type "'.$type.'" on "'.$var.'". Must be one "string", "int", "float" and "date"';
					exit;
				}
			}
		}
		elseif ($origin{$i} == 'S' || $origin{$i} == 's')
		{
			
			if (isset($_SESSION[$var]))
			{
				// we check first for arrays
				if (is_array($_SESSION[$var])){
					if ($type != "unknown_type" && $type != "array") {
						echo '<b>Error!</b> The '.$var.' field must be a '.$type.". Array passed instead.";
						exit;
					}
					$array = $_SESSION[$var];
					if (!array_walk_recursive($array, "userinput_to_plainstring")) {
						echo '<b>Error!</b> An error occured while walking array '.$var.'.';
						exit;
					}
					
					return $array;
				}
				
				if ($type=='string' || $type=='unknown_type')
					return $_SESSION[$var];
				elseif ($type=='int')
				{
					if (!check_integer($_SESSION[$var]))
					{
						echo '<b>Error!</b> The '.$var.' field must be an integer';
						exit;
					}
					return $_SESSION[$var];
				}
				elseif ($type=='float')
				{
					if (!is_numeric($_SESSION[$var]))
					{
						echo '<b>Error!</b> The '.$var.' field must be a number';
						exit;
					}
					return $_SESSION[$var];
				}
				elseif ($type=='date')
				{
					$date = strtotime($_SESSION[$var]);
					if (!$date || $date == -1)
					{
						echo '<b>Error!</b> The '.$var.' field must be a date';
						exit;
					}
					return $_SESSION[$var];
				}
				elseif ($type=='array')
				{
					echo '<b>Error while walking the array '.$var.'</b>';
					exit;
				}
				else 
				{
					echo '<b>Error!</b> Unknown required type "'.$type.'" on "'.$var.'". Must be one "string", "int", "float" and "date"';
					exit;
				}
			}
		}
		elseif ($origin{$i} == 'C' || $origin{$i} == 'c')
		{
			if (isset($_COOKIE[$var]))
			{
				// we check first for arrays
				if (is_array($_COOKIE[$var])){
					if ($type != "unknown_type" && $type != "array") {
						echo '<b>Error!</b> The '.$var.' field must be a '.$type.". Array passed instead.";
						exit;
					}
					$array = $_COOKIE[$var];
					if (!array_walk_recursive($array, "userinput_to_plainstring")) {
						echo '<b>Error!</b> An error occured while walking array '.$var.'.';
						exit;
					}
					
					return $array;
				}
				
				if ($type=='string' || $type=='unknown_type')
					return $_COOKIE[$var];
				elseif ($type=='int')
				{
					if (!check_integer($_COOKIE[$var]))
					{
						echo '<b>Error!</b> The '.$var.' field must be an integer';
						exit;
					}
					return $_COOKIE[$var];
				}
				elseif ($type=='float')
				{
					if (!is_numeric($_COOKIE[$var]))
					{
						echo '<b>Error!</b> The '.$var.' field must be a number';
						exit;
					}
					return $_COOKIE[$var];
				}
				elseif ($type=='date')
				{
					$date = strtotime($_COOKIE[$var]);
					if (!$date || $date == -1)
					{
						echo '<b>Error!</b> The '.$var.' field must be a date';
						exit;
					}
					return $_COOKIE[$var];
				}
				elseif ($type=='array')
				{
					echo '<b>Error!</b> The '.$var.' field must be an array';
					exit;
				}
				else 
				{
					echo '<b>Error!</b> Unknown required type "'.$type.'" on "'.$var.'". Must be one "string", "int", "float" and "date"';
					exit;
				}
			}
		}
		
	}
	
	if (!$compulsory)
		return $default_value;
	else 
	{
		echo '<b>Error!</b> You must provide the '.$var.' field';
		exit;
	}

}

function check_integer($var)
{
	if (!is_numeric($var))
		return false;
	if ($var != intval($var))
		return false;
		
	return true;
}

/**
 * Used when writing SQL INSERT OR UPDATE statement:
 * this protect the string and surround it with quotes unless it is NULL.
 *
 * @param unknown_type $var
 * @return unknown
 */
function string_to_sql($var) {
	if ($var !== null)
			return "'".plainstring_to_dbprotected($var)."'";
		else 
			return 'NULL';
}

?>