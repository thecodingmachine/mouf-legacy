<h1>Packages List</h1>
<h1>DO NOT USE THIS INTERFACE, THIS IS NOT READY YET!</h1>

<div id="packageList">
<?php 
$oldGroup = "";
foreach ($this->moufPackageList as $package) {
	if ($package->getDescriptor()->getGroup() != $oldGroup) {
		echo "<div class='group'>Group: <b>".htmlentities($package->getDescriptor()->getGroup())."</b></div>";
		$oldGroup = $package->getDescriptor()->getGroup();
	}
	echo "<div class='outerpackage'>";
	echo "<div class='package'><span class='packagename'>".htmlentities($package->getDisplayName())."</span> <span class='packgeversion'>(version ".htmlentities($package->getDescriptor()->getVersion()).")</span>";
	if ($package->getShortDescription() || $package->getDocUrl()) {
		echo "<div class='packagedescription'>";
		echo $package->getShortDescription();
		if ($package->getShortDescription() && $package->getDocUrl()) {
			echo "<br/>";
		}
		if ($package->getDocUrl()) {
			echo "Documentation URL: <a href='".htmlentities($package->getDocUrl())."'>".$package->getDocUrl()."</a>";
		}
		echo "</div>";
	}
	$packageXmlPath = $package->getDescriptor()->getPackageXmlPath();
	if (!$this->moufManager->isPackageEnabled($packageXmlPath)) {
		echo "<form action='enablePackage' method='POST'>";
		echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
		echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
		echo "<button>Enable</button>";
		echo "</form>";
	} else {
		echo "<form action='disablePackage' method='POST'>";
		echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
		echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
		echo "<button>Disable</button>";
		echo "</form>";
	}
	echo "</div></div>";
}
?>

</div>
<br/>
<br/>