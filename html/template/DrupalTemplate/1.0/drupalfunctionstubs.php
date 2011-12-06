<?php

if (!function_exists("t")) {
	/**
	 * Replacement for the "t" function in Drupal.
	 *
	 */
	function t($label, $paramsArr=null, $langCode = null) {
		// Ignoring $paramsArr and $langCode
		return iMsg($label);
	}
}

function arg(){
	return null;
}

function theme(){
	return false;
}

function base_path() {
	return $localFilePath;
}

function path_to_theme() {
	return "";
}

function check_plain($text) {
  return $text;
}

function check_url($uri) {
  return $uri;
}

function module_exists($module) {
  return false;
}

function variable_get($name, $default) {
  return $default;
}

function theme_get_setting() {
	return false;
}

function theme_get_settings() {
	return array();
}

function node_get_types() {
	return false;
}

function drupal_add_css(){
	return array();
}

function drupal_get_path(){
	return "";
}

//Parse info file
function drupal_parse_info_file($filename) {
  $info = array();
  $constants = get_defined_constants();

  if (!file_exists($filename)) {
    return $info;
  }

  $data = file_get_contents($filename);
  if (preg_match_all('
    @^\s*                           # Start at the beginning of a line, ignoring leading whitespace
    ((?:
      [^=;\[\]]|                    # Key names cannot contain equal signs, semi-colons or square brackets,
      \[[^\[\]]*\]                  # unless they are balanced and not nested
    )+?)
    \s*=\s*                         # Key/value pairs are separated by equal signs (ignoring white-space)
    (?:
      ("(?:[^"]|(?<=\\\\)")*")|     # Double-quoted string, which may contain slash-escaped quotes/slashes
      (\'(?:[^\']|(?<=\\\\)\')*\')| # Single-quoted string, which may contain slash-escaped quotes/slashes
      ([^\r\n]*?)                   # Non-quoted string
    )\s*$                           # Stop at the next end of a line, ignoring trailing whitespace
    @msx', $data, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
      // Fetch the key and value string
      $i = 0;
      foreach (array('key', 'value1', 'value2', 'value3') as $var) {
        $$var = isset($match[++$i]) ? $match[$i] : '';
      }
      $value = stripslashes(substr($value1, 1, -1)) . stripslashes(substr($value2, 1, -1)) . $value3;

      // Parse array syntax
      $keys = preg_split('/\]?\[/', rtrim($key, ']'));
      $last = array_pop($keys);
      $parent = &$info;

      // Create nested arrays
      foreach ($keys as $key) {
        if ($key == '') {
          $key = count($parent);
        }
        if (!isset($parent[$key]) || !is_array($parent[$key])) {
          $parent[$key] = array();
        }
        $parent = &$parent[$key];
      }

      // Handle PHP constants.
      if (isset($constants[$value])) {
        $value = $constants[$value];
      }

      // Insert actual value
      if ($last == '') {
        $last = count($parent);
      }
      $parent[$last] = $value;
    }
  }

  return $info;
}

?>