<h1>Packages List</h1>

<div id="packageList">
<?php 
$oldGroup = "";
foreach ($this->moufPackageList as $package) {
	if ($package->getDescriptor()->getGroup() != $oldGroup) {
		echo "<div class='group'>Group: <b>".htmlentities($package->getDescriptor()->getGroup())."</b></div>";
		$oldGroup = $package->getDescriptor()->getGroup();
	}
	echo "<div class='package'>".htmlentities($package->getDisplayName()." (version ".$package->getDescriptor()->getVersion()).")"."</div>";
}
?>

</div>
<br/>
<br/>