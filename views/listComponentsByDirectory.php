<h1>Available component instances</h1>

<?php
foreach ($this->instancesByPackage as $package=>$instancesByClass) {
	echo "<div class='directorytitle'>$package</div>";
	echo "<div class='directorycontent'>";
	foreach ($instancesByClass as $class=>$instances) {
		foreach ($instances as $instance) {
			echo "<a href='".ROOT_URL."mouf/mouf/displayComponent?name=".plainstring_to_urlprotected($instance)."&selfedit=".$this->selfedit."'>";
			echo plainstring_to_htmlprotected($instance);
			echo "</a> - ".plainstring_to_htmlprotected($class)."<br/>";	
		}
	}
	echo "</div>";
}

?>