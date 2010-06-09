<?php

define("SPLASH_PATH", dirname(__FILE__)."/");
//define("ROOT_PATH", realpath(SPLASH_PATH."..")."/");
define("SPLASH_CONTROLLERS_PATHS",SPLASH_PATH.'controllers/');
define("SPLASH_MODELS_PATHS",SPLASH_PATH.'models/');
define("SPLASH_UTILS_PATHS",SPLASH_PATH.'utils/');
define("SPLASH_VIEWS_PATHS",SPLASH_PATH.'views/');
define("SPLASH_RESOURCES_PATHS",SPLASH_PATH.'resources/');
define("SPLASH_VALIDATORS_PATH",SPLASH_PATH.'validators/');
define("SPLASH_FILTERS_PATH",SPLASH_PATH.'filters/');
define("TRACKING", true);

// Loads the config file at the root of the website.
require_once 'models/CommonUtils.php';

//Xaja
//require_once XAJA_PATH."include.php";

/*if (!defined("USE_ANNOTATION_CACHE") || !USE_ANNOTATION_CACHE) {
	stubAnnotationCache::flush();
}*/

// CONTROLLERS
//require_once 'models/Scopable.php';
require_once 'controllers/Controller.php';

// Utils
require_once 'utils/ExceptionUtils.php';

// MODELS
require_once 'models/Splash.php';
require_once 'models/SplashAction.php';
require_once 'models/ApplicationException.php';

// Validators
require_once 'validators/ValidatorInterface.php';
require_once 'validators/AbstractValidator.php';
require_once 'validators/NumberValidator.php';
require_once 'validators/EmailValidator.php';

// Annotations
require_once 'utils/annotations/AnnotationException.php';
//require_once 'utils/annotations/stubActionAnnotation.php';
require_once 'utils/annotations/paramAnnotation.php';
require_once 'utils/annotations/ValidatorException.php';

// Filters
require_once 'models/FilterUtils.php';
require_once 'filters/AbstractFilter.php';
//require_once 'filters/Logged.php';
require_once 'filters/RequireHttpsAnnotation.php';
//require_once 'filters/AdminAnnotation.php';
require_once 'filters/RedirectToHttpAnnotation.php';
require_once 'filters/XajaAnnotation.php';

?>
