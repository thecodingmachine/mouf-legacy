<?php /* @var $this PackageController */ ?>
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

<p>
<a href="javascript:void(0)" id="toggleall">Toggle all</a>
<a href="javascript:void(0)" id="viewinstalled">View installed packages only</a>
<a href="javascript:void(0)" id="viewavailable" style="display:none">View all available packages</a>
</p>
<br/>
<div id="packageList">
<?php
displayGroup('.', $this->moufPackageRoot, '', $this); 

function displayGroup($name, MoufGroupDescriptor $group, $fullName, PackageController $controller) {
	if ($fullName != '') {
		echo "<div class='treegroup'>\n";
		echo "<div class='group groupplus'>";
		echo "<div style='float:right'>";
		echo "Group name: ".$fullName;
		echo "</div><b>";
		echo $name;
		echo "</b></div>";
		echo "<div class='groupcontainer' style='display:none'>";
	}
	foreach ($group->subGroups as $subgroupname=>$subgroup) {
		if ($fullName == "") {
			$newFullName = $subgroupname;
		} else {
			$newFullName = $fullName."/".$subgroupname;
		}
		displayGroup($subgroupname, $subgroup, $newFullName, $controller);
	}
	foreach ($group->packages as $packagename=>$packageversionscontainer) {
		displayPackageVersionContainer($packagename, $packageversionscontainer, $controller);
	}
	if ($fullName != '') {	
		echo "</div>";
		echo "</div>";
	}
}

function displayPackageVersionContainer($packagename, MoufPackageVersionsContainer $packageversionscontainer, PackageController $controller) {
	
	$enabledVersion = false;
	
	// First, let's get through the versions, and see if one is enabled...
	foreach ($packageversionscontainer->packages as $package) {
		/* @var $package MoufPackage */
		
		$packageXmlPath = $package->getDescriptor()->getPackageXmlPath();
		$isPackageEnabled = $controller->moufManager->isPackageEnabled($packageXmlPath);

		if ($isPackageEnabled) {
			$enabledVersion = $package->getDescriptor()->getVersion();
			break;
		}
	}
	
	$isFirst = true;

	echo "<div class='packageversions'>";
	foreach ($packageversionscontainer->packages as $package) {
		/* @var $package MoufPackage */
		
		if (($isFirst && $enabledVersion === false) || $enabledVersion == $package->getDescriptor()->getVersion()) {
			$display = true;
		} else {
			$display = false;
		}
		
		$packageXmlPath = $package->getDescriptor()->getPackageXmlPath();
		$isPackageEnabled = $controller->moufManager->isPackageEnabled($packageXmlPath);
		
		echo "<div class='outerpackage' ".(($display)?"":"style='display:none'").">";
		echo "<div class='package ".(($isPackageEnabled)?"packageenabled":"packagedisabled")."'>";
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
		if ($display && count($packageversionscontainer->packages) > 1) {
			echo "<a href='javascript:void(0)' class='viewotherversions'>View other versions</a>";
		}
		
		if ($enabledVersion !== false && $enabledVersion != $package->getDescriptor()->getVersion()) {
			echo "<form action='upgradePackage' method='POST'>";
			echo "<input type='hidden' name='selfedit' value='".$controller->selfedit."' />";
			echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
			if (MoufPackageDescriptor::compareVersionNumber($package->getDescriptor()->getVersion(), $enabledVersion) > 0) {
				echo "<button>Upgrade to this package</button>";
			} else {
				echo "<button>Downgrade to this package</button>";
			}
			echo "</form>";
		} else if (!$isPackageEnabled) {
			echo "<form action='enablePackage' method='POST'>";
			echo "<input type='hidden' name='selfedit' value='".$controller->selfedit."' />";
			echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
			echo "<button>Enable</button>";
			echo "</form>";
		} else {
			echo "<form action='disablePackage' method='POST'>";
			echo "<input type='hidden' name='selfedit' value='".$controller->selfedit."' />";
			echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
			echo "<button>Disable</button>";
			echo "</form>";
		}
		echo "</div></div></div>";
		$isFirst = false;
	}
	echo "</div>";
}

?>
<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery(".treegroup .group").click(function(evt) {
		// TODO: toggle with a VERTICAL slide effect
		jQuery(evt.currentTarget).parent().children(".groupcontainer").slideToggle('normal');
		if (jQuery(evt.currentTarget).hasClass('groupminus')) {
			jQuery(evt.currentTarget).addClass('groupplus');
			jQuery(evt.currentTarget).removeClass('groupminus');
		} else {
			jQuery(evt.currentTarget).addClass('groupminus');
			jQuery(evt.currentTarget).removeClass('groupplus');
		}
	});

	jQuery("#toggleall").click(function() {
		jQuery('.treegroup .group').removeClass('groupplus');
		jQuery('.treegroup .group').addClass('groupminus');
		jQuery('.groupcontainer').show();
	});

	jQuery("#viewinstalled").click(function() {
		jQuery('.treegroup .group').removeClass('groupplus');
		jQuery('.treegroup .group').addClass('groupminus');
		jQuery('.groupcontainer').show();
		jQuery('.packagedisabled').hide();
		jQuery('#viewinstalled').hide();
		jQuery('#viewavailable').show();		
	});
	
	jQuery("#viewavailable").click(function() {
		jQuery('.packagedisabled').show();
		jQuery('#viewinstalled').show();
		jQuery('#viewavailable').hide();		
	});

	jQuery(".viewotherversions").click(function(evt) {
		jQuery(evt.currentTarget).parent().parent().parent().parent().children(".outerpackage").slideDown();
		jQuery(evt.currentTarget).hide();
	});

});

</script>



<?php 
//$oldGroup = "";
//$oldName = "";
//$previousWasOldVersion = false;
//foreach ($this->moufPackageList as $package) {
//	$isOldVersion = false;
//	if ($package->getDescriptor()->getGroup()."/".$package->getDescriptor()->getName() == $oldName) {
//		$isOldVersion = true;
//	} else {
//		$oldName = $package->getDescriptor()->getGroup()."/".$package->getDescriptor()->getName();
//		// A container for the packages that have the same name. 
//		//echo "<div class='packagesContainer'>";
//	}
//	if ($package->getDescriptor()->getGroup() != $oldGroup) {
//		echo "<div class='group'>Group: <b>".htmlentities($package->getDescriptor()->getGroup())."</b></div>";
//		$oldGroup = $package->getDescriptor()->getGroup();
//	}
//	
//	// If this is the first package in the list to be the old version of the upper package.
//	if ($previousWasOldVersion == false && $isOldVersion == true) {
//		echo "show older versions";
//	}
//	
//	echo "<div class='outerpackage'>";
//	echo "<div class='package'>";
//	echo "<div class='packageicon'>";
//	if ($package->getLogoPath() != null) {
//		if (strpos($package->getLogoPath(), "http://") === 0 || strpos($package->getLogoPath(), "https://") === 0) {
//			echo "<img alt='' style='float:left' src='".$package->getLogoPath()."'>";
//		} else {
//			echo "<img alt='' style='float:left' src='".ROOT_URL."plugins/".$package->getPackageDirectory()."/".$package->getLogoPath()."'>";
//		}
//	}
//	echo "</div>";
//	echo "<div class='packagetext'>";
//	echo "<span class='packagename'>".htmlentities($package->getDisplayName())."</span> <span class='packgeversion'>(version ".htmlentities($package->getDescriptor()->getVersion()).")</span>";
//	if ($package->getShortDescription() || $package->getDocUrl()) {
//		echo "<div class='packagedescription'>";
//		echo $package->getShortDescription();
//		if ($package->getShortDescription() && $package->getDocUrl()) {
//			echo "<br/>";
//		}
//		if ($package->getDocUrl()) {
//			echo "Documentation URL: <a href='".htmlentities($package->getDocUrl())."'>".$package->getDocUrl()."</a>";
//		}
//		echo "</div>";
//	}
//	$packageXmlPath = $package->getDescriptor()->getPackageXmlPath();
//	if (!$this->moufManager->isPackageEnabled($packageXmlPath)) {
//		echo "<form action='enablePackage' method='POST'>";
//		echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
//		echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
//		echo "<button>Enable</button>";
//		echo "</form>";
//	} else {
//		echo "<form action='disablePackage' method='POST'>";
//		echo "<input type='hidden' name='selfedit' value='".$this->selfedit."' />";
//		echo "<input type='hidden' name='name' value='".htmlentities($packageXmlPath)."' />";
//		echo "<button>Disable</button>";
//		echo "</form>";
//	}
//	echo "</div></div></div>";
//	$previousWasOldVersion = $isOldVersion;
//}
?>

</div>
<br/>
<br/>