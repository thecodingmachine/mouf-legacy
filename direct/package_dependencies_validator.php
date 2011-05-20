<?php
// Rewrites the MoufRequire file from the MoufComponents file, and the admin too.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	//require_once '../../Mouf.php';
	require_once '../../MoufComponents.php';
	require_once '../../MoufUniversalParameters.php';
} else {
	require_once '../MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../MoufUniversalParameters.php';
	MoufManager::switchToHidden();
	//require_once '../MoufAdmin.php';
	require_once '../MoufAdminComponents.php';
}

require_once '../MoufPackageManager.php';

$moufManager = MoufManager::getMoufManager();
$packagesXmlFiles = $moufManager->listEnabledPackagesXmlFiles();

$errorList = array();

foreach ($packagesXmlFiles as $packageXmlFile) {
	$packageManager = new MoufPackageManager("../../plugins");
	$package = $packageManager->getPackage($packageXmlFile);
	$dependencies = $package->getDependenciesAsDescriptors();
	
	$found = false;
	foreach ($dependencies as $dependency) {
		$tooLate = false;
		/* @var $dependency MoufDependencyDescriptor */
		// Let's test if each dependency is available, and in the first part of the dependencies.
		foreach ($packagesXmlFiles as $packageXmlFileCheck) {
			if ($packageXmlFileCheck == $packageXmlFile) {
				// After current package, we are too late, we should change the order of the packages. 
				$tooLate = true;
			}
			
			$installedPackageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($packageXmlFileCheck);
			if ($dependency->getGroup() == $installedPackageDescriptor->getGroup()
				&& $dependency->getName() == $installedPackageDescriptor->getName()) {
				if (!$dependency->isCompatibleWithVersion($installedPackageDescriptor->getVersion())) {
					$errorList[] = "For package ".$installedPackageDescriptor->getGroup()."/".$installedPackageDescriptor->getName().", installed version is ".$installedPackageDescriptor->getVersion().".
									However, the package ".$package->getDescriptor()->getGroup()."/".$package->getDescriptor()->getName()."/".$package->getDescriptor()->getVersion()."
									requires the version of this package to be ".$dependency->getVersion().".<br/>";
				} else {
					if ($tooLate) {
						$errorList[] = "The package ".$package->getDescriptor()->getGroup()."/".$package->getDescriptor()->getName()."/".$package->getDescriptor()->getVersion()."
								requires the package ".$installedPackageDescriptor->getGroup()."/".$installedPackageDescriptor->getName()."/".$installedPackageDescriptor->getVersion().".
								This package is indeed included, but too late! Therefore, the dependency might not be satisfied.<br/>";
					} else {
						$found = true;
					}
				}
			}
		}
		
		if (!$found) {
			$errorList[] = "Unable to find package ".$dependency->getGroup()."/".$dependency->getName().", version ".$dependency->getVersion().".
							This package is package requested by package ".$package->getDescriptor()->getGroup()."/".$package->getDescriptor()->getName()."/".$package->getDescriptor()->getVersion().".<br/>";
		} else {
			$found = false;
		}
	}
	
}

if ($errorList) {
	$jsonObj['code'] = "error";
	$jsonObj['html'] = implode($errorList, "");
} else {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "All packages dependencies are satisfied.";
}

echo json_encode($jsonObj);
