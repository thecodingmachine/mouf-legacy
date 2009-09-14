<?php
session_start();

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
//require_once ROOT_PATH.'config.php';
require_once 'constants.php';

//Xaja
//require_once XAJA_PATH."include.php";

// UTILS
//require_once 'utils/string_utils.php';
require_once 'include/stubbles/projects/dist/config/php/config.php';
require_once 'include/stubbles/src/main/php/net/stubbles/stubClassLoader.php';
require_once 'include/stubbles/src/main/php/net/stubbles/reflection/reflection.php';

if (!defined("USE_ANNOTATION_CACHE") || !USE_ANNOTATION_CACHE) {
	stubAnnotationCache::flush();
}

// CONTROLLERS
//require_once 'models/Scopable.php';
require_once 'controllers/Controller.php';

// Utils
require_once 'utils/ExceptionUtils.php';
require_once 'utils/SplashSessionUtils.php';

// MODELS
require_once 'models/SplashObject.php';
require_once 'models/Splash.php';
//require_once 'models/AdminBag.php';
//require_once 'models/SplashUrlAnalyzer.php';
require_once 'models/ApplicationException.php';
//require_once 'models/TemplateInterface.php';

// Validators
require_once 'validators/ValidatorInterface.php';
require_once 'validators/AbstractValidator.php';
require_once 'validators/NumberValidator.php';
require_once 'validators/EmailValidator.php';

// Annotations
require_once 'utils/annotations/AnnotationException.php';
require_once 'utils/annotations/stubActionAnnotation.php';
require_once 'utils/annotations/stubVarAnnotation.php';
require_once 'utils/annotations/ValidatorException.php';

// Filters
require_once 'models/FilterUtils.php';
require_once 'filters/AbstractFilter.php';
require_once 'filters/Logged.php';
require_once 'filters/RequireHttps.php';
require_once 'filters/Admin.php';
require_once 'filters/RedirectToHttp.php';
require_once 'filters/stubXajaAnnotation.php';


// DB CONNECTION
//Log::trace("DB _init");
//require_once TDBM_PATH."tdbm.php";
//include_once SPLASH_PATH.'db_config.php';
//DBM_Object::setDefaultAutoSaveMode(false);

//Internationalization
require_once 'utils/languageUtils.php';
?>
