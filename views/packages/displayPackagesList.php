<h1>Packages List</h1>

<?php 
if ($this->validationMsg != null) {
	echo '<div class="success">';
	if ($this->validationMsg == "enable") {
		echo "Packages successfully enabled: ";
	} else {
		echo "Packages successfully disabled: ";
	}
	echo implode(", ", $this->validationPackageList);
	echo '</div>';
?>
	<script type="text/javascript">
	setTimeout(function() {
		jQuery('.success').fadeOut(3000);
	}, 7000);
	</script>
<?php 
}
?>


<div id="packageList">
<?php 
$oldGroup = "";
foreach ($this->moufPackageList as $package) {
	if ($package->getDescriptor()->getGroup() != $oldGroup) {
		echo "<div class='group'>Group: <b>".htmlentities($package->getDescriptor()->getGroup())."</b></div>";
		$oldGroup = $package->getDescriptor()->getGroup();
	}
	echo "<div class='outerpackage'>";
	echo "<div class='package'>";
	echo "<div class='packageicon'>";
	if ($package->getLogoPath() != null) {
		if (strpos($package->getLogoPath(), "http://") === 0 || strpos($package->getLogoPath(), "https://") === 0) {
			echo "<img alt='' style='float:left' src='".$package->getLogoPath()."'>";
		} else {
			echo "<img alt='' style='float:left' src='".ROOT_URL."plugins/".$package->getPackageDirectory()."/".$package->getLogoPath()."'>";
		}
	}
	echo "</div>";
	echo "<div class='packagetext'>";
	echo "<span class='packagename'>".htmlentities($package->getDisplayName())."</span> <span class='packgeversion'>(version ".htmlentities($package->getDescriptor()->getVersion()).")</span>";
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
	echo "</div></div></div>";
}
?>

</div>
<br/>
<br/>