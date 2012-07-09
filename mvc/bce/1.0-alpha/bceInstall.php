<?php
// First, let's request the install utilities
require_once '../../../../mouf/actions/InstallUtils.php';

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

//Let's automatically create validators for the components that are not parametized (eg : don't create a MinMaxRangeValidator)...
$classes = array(
		'HiddenRenderer',
		'MultipleSelectFieldRenderer',
		"RequiredValidator",
		'SelectFieldRenderer',
		'TextFieldRenderer',
		'JQueryValidateHandler'
);

//now create default jquery and jquery UI instances that will be used
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');

$jQueryUiLib = $moufManager->createInstance("WebLibrary");
$jQueryUIInstanceName = InstallUtils::getInstanceName("jQueryUI", $moufManager);
$jQueryUiLib->setName($jQueryUIInstanceName);
$jQueryUiLib->getProperty("jsFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-ui/1.8.20/js/jquery-1.7.2.min.js', 
	'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js'
));
$jQueryUiLib->getProperty("cssFiles")->setValue(array(
	'plugins/javascript/jquery/jquery-ui/1.8.20/css/ui-darkness/jquery-ui-1.8.20.custom.css'
));
$jQueryUiLib->getProperty("renderer")->setValue($renderer);

$bceWebLibrairyManager = $moufManager->createInstance("WebLibraryManager");
$webLibManagerInstanceName = InstallUtils::getInstanceName("bceLibManager", $moufManager);
$bceWebLibrairyManager->setName($webLibManagerInstanceName);
$bceWebLibrairyManager->getProperty('webLibraries')->setValue(array($jQueryLib, $jQueryUiLib));

$baseSkinLib = $moufManager->createInstance("WebLibrary");
$baseSkinLibName = InstallUtils::getInstanceName("bceBaseSkin", $moufManager);
$jQueryInstanceNames->setName($baseSkinLibName);
$baseSkinLib->getProperty("cssFiles")->setValue(array(
	"plugins/mvc/bce/1.0-alpha/form_renderer/base/css/basic.css"
));
$baseSkinLib->getProperty("renderer")->setValue($renderer);


$baseRendererInstance = $moufManager->createInstance('BaseRenderer');
$baseRendererInstanceName = InstallUtils::getInstanceName("BaseRenderer", $moufManager);
$baseRendererInstance->setName($baseRendererInstanceName);
$baseRendererInstance->getProperty("skin")->setValue($baseSkinLib);

InstallUtils::massCreate($classes, $moufManager);

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();