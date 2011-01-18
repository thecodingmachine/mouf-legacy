<h1>Packages List</h1>
<h2>You selected this package:</h2>

<?php 
	echo "<div class='group'>Group: <b>".htmlentities($this->package->getDescriptor()->getGroup())."</b></div>";
	echo "<div class='outerpackage'>";
	echo "<div class='package'><span class='packagename'>".htmlentities($this->package->getDisplayName())."</span> <span class='packgeversion'>(version ".htmlentities($this->package->getDescriptor()->getVersion()).")</span>";
	if ($this->package->getShortDescription() || $this->package->getDocUrl()) {
		echo "<div class='packagedescription'>";
		echo $this->package->getShortDescription();
		if ($this->package->getShortDescription() && $this->package->getDocUrl()) {
			echo "<br/>";
		}
		if ($this->package->getDocUrl()) {
			echo "Documentation URL: <a href='".htmlentities($this->package->getDocUrl())."'>".$this->package->getDocUrl()."</a>";
		}
		if ($this->package->getCurrentLocation() != null) {
			echo "<br/>This package will be downloaded from repository '".plainstring_to_htmlprotected($this->package->getCurrentLocation()->getName())."'";
		}
		echo "</div>";
	}	
	echo "</div></div>";
?>

<h2>The following packages needs to be enabled too:</h2>

<div id="packageList">
<?php 
$oldGroup = "";
foreach ($this->moufDependencies as $package) {
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
		if ($package->getCurrentLocation() != null) {
			echo "<br/>This package will be downloaded from repository '".plainstring_to_htmlprotected($package->getCurrentLocation()->getName())."'";
		}
		echo "</div>";
	}
	
	echo "</div></div>";
}
$packageXmlPath = $this->package->getDescriptor()->getPackageXmlPath();
echo "<form action='enablePackage' method='POST'>";
echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
echo "<input type='hidden' name='group' value='".htmlentities($this->package->getDescriptor()->getGroup())."' />";
echo "<input type='hidden' name='name' value='".htmlentities($this->package->getDescriptor()->getName())."' />";
echo "<input type='hidden' name='version' value='".htmlentities($this->package->getDescriptor()->getVersion())."' />";
echo "<input type='hidden' name='confirm' value='true' />";
echo "<button>Enable all listed packages</button>";
echo "</form>";
?>



</div>
<br/>
<br/>