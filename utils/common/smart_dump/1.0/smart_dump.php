<?php

function smart_dump_recurs($variable) {
    if ($variable === true) {
        return 'true';
    } else if ($variable === false) {
        return 'false';
    } else if ($variable === null) {
        return 'null';
    } else if (is_array($variable)) {
        $html = "<table class='dump' cellspacing='0' cellpadding='0'>\n";
        $html .= "<thead><tr><td><b>KEY</b></td><td><b>VALUE</b></td></tr></thead>\n";
        $html .= "<tbody>\n";
        foreach ($variable as $key => $value) {
            $value = smart_dump_recurs($value);
            $html .= "<tr><td class='key'>$key</td><td class='value'>$value</td></tr>\n";
        }
        $html .= "</tbody>\n";
        $html .= "</table>";
        return $html;
    } else {
        return strval($variable);
    }
}

/**
 * Displays a variable with a smart layout in HTML
 *
 * @param mixed $variable
 * @return string
 */
function smart_dump($variable) {
	$dump = smart_dump_recurs($variable);
	$script="
	<style>
<!--
table.dump {
	border:2px solid #8aF;
}
table.dump tr td.key {
	border:1px solid #eee;font-weight:bold
}	
table.dump tr td.value {
	border:1px solid #eee;color:#3a3
}	
-->
</style>";
	
	return $script.$dump;
}
?>