<?php


//Xaja
//require_once XAJA_PATH."include.php";

/*if (!defined("USE_ANNOTATION_CACHE") || !USE_ANNOTATION_CACHE) {
	stubAnnotationCache::flush();
}*/

// CONTROLLERS
//require_once 'models/Scopable.php';
require_once 'controllers/WebServiceInterface.php';
require_once 'controllers/WebService.php';

// MODELS
require_once 'models/Splash.php';
require_once 'models/SplashAction.php';


if (file_exists(dirname(__FILE__).'/resources/message_'.$i18n_lg.'.php')){
	@include_once dirname(__FILE__).'/resources/message.php';
	require_once dirname(__FILE__).'/resources/message_'.$i18n_lg.'.php';
}
else{
	// No error if the file is not found.
	@include_once dirname(__FILE__).'/resources/message.php';
}

?>