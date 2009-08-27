<h1>Available component instances</h1>

<?php
if (is_array($this->moufManager->getInstancesList())) {
	foreach ($this->moufManager->getInstancesList() as $key=>$value) {
	
		echo "<a href='".ROOT_URL."mouf/mouf/displayComponent?name=".plainstring_to_urlprotected($key)."&selfedit=".$this->selfedit."'>";
		echo plainstring_to_htmlprotected($key);
		echo "</a> - ".plainstring_to_htmlprotected($value)."<br/>";
	}
}
?>