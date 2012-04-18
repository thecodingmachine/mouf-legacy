<?php
/**
 * This is a file automatically generated by the Mouf framework. Do not modify it, as it could be overwritten.
 */

// Register autoloadable classes
MoufManager::getMoufManager()->registerAutoloadedClasses(array (
  'TcmUtilsException' => 'mouf/../plugins/utils/common/getvars/1.1/TcmUtilsException.php',
  'AndCondition' => 'mouf/../plugins/utils/common/conditioninterface/1.0/AndCondition.php',
  'OrCondition' => 'mouf/../plugins/utils/common/conditioninterface/1.0/OrCondition.php',
  'NotCondition' => 'mouf/../plugins/utils/common/conditioninterface/1.0/NotCondition.php',
  'TrueCondition' => 'mouf/../plugins/utils/common/conditioninterface/1.0/TrueCondition.php',
  'FalseCondition' => 'mouf/../plugins/utils/common/conditioninterface/1.0/FalseCondition.php',
  'HtmlFromFile' => 'mouf/../plugins/html/htmlelement/1.0/HtmlFromFile.php',
  'HtmlFromFunction' => 'mouf/../plugins/html/htmlelement/1.0/HtmlFromFunction.php',
  'HtmlString' => 'mouf/../plugins/html/htmlelement/1.0/HtmlString.php',
  'PHPExcel' => 'mouf/../plugins/utils/export/phpexcel/1.7.5/PHPExcel.php',
  'PHPExcel_Autoloader' => 'mouf/../plugins/utils/export/phpexcel/1.7.5/PHPExcel.php',
  'PHPExcel_Shared_ZipStreamWrapper' => 'mouf/../plugins/utils/export/phpexcel/1.7.5/PHPExcel.php',
  'PHPExcel_Shared_String' => 'mouf/../plugins/utils/export/phpexcel/1.7.5/PHPExcel.php',
  'BaseTemplate' => 'mouf/../plugins/html/template/BaseTemplate/1.0/BaseTemplate.php',
  'HtmlJSJQuery' => 'mouf/../plugins/javascript/jquery/jquery/1.6/HtmlJSJQuery.php',
  'HtmlJSJQueryFixedHeaderTable' => 'mouf/../plugins/javascript/jquery/jquery-fixedheadertable/1.3/HtmlJSJQueryFixedHeaderTable.php',
  'FineMessageLanguage' => 'mouf/../plugins/utils/i18n/fine/2.1/FineMessageLanguage.php',
  'BrowserLanguageDetection' => 'mouf/../plugins/utils/i18n/fine/2.1/language/BrowserLanguageDetection.php',
  'DomainLanguageDetection' => 'mouf/../plugins/utils/i18n/fine/2.1/language/DomainLanguageDetection.php',
  'FixedLanguageDetection' => 'mouf/../plugins/utils/i18n/fine/2.1/language/FixedLanguageDetection.php',
  'CascadingLanguageDetection' => 'mouf/../plugins/utils/i18n/fine/2.1/language/CascadingLanguageDetection.php',
  'FinePHPArrayTranslationService' => 'mouf/../plugins/utils/i18n/fine/2.1/translate/FinePHPArrayTranslationService.php',
  'FineCurrencyUtils' => 'mouf/../plugins/utils/i18n/fine/2.1/misc/FineCurrencyUtils.php',
  'Menu' => 'mouf/../plugins/html/widgets/menu/1.0/Menu.php',
  'MenuItem' => 'mouf/../plugins/html/widgets/menu/1.0/MenuItem.php',
  'MenuItemStyleIcon' => 'mouf/../plugins/html/widgets/menu/1.0/MenuItemStyleIcon.php',
  'SplashTemplate' => 'mouf/../plugins/html/template/SplashTemplate/2.0/SplashTemplate.php',
  'SplashMenuRenderer' => 'mouf/../plugins/html/template/SplashTemplate/2.0/SplashMenuRenderer.php',
  'HtmlJSJQueryAutoGrow' => 'mouf/../plugins/javascript/jquery/jquery-autogrow/1.2.2/HtmlJSJQueryAutoGrow.php',
  'HtmlJSJQueryUI' => 'mouf/../plugins/javascript/jquery/jquery-ui/1.7.2/HtmlJSJQueryUI.php',
  'HtmlJSJQueryFileTree' => 'mouf/../plugins/javascript/jquery/jqueryFileTree/1.01/HtmlJSJQueryFileTree.php',
  'HtmlJSJit' => 'mouf/../plugins/javascript/jit/1.1.2/HtmlJSJit.php',
  'HtmlJSPrototype' => 'mouf/../plugins/javascript/prototype/1.6.0.1/HtmlJSPrototype.php',
  'ErrorLogLogger' => 'mouf/../plugins/utils/log/errorlog_logger/1.1/ErrorLogLogger.php',
  'UserDaoException' => 'mouf/../plugins/security/userservice/1.0/UserDaoException.php',
  'UserServiceException' => 'mouf/../plugins/security/userservice/1.0/UserServiceException.php',
  'MoufUserService' => 'mouf/../plugins/security/userservice/1.0/MoufUserService.php',
  'UserFileBean' => 'mouf/../plugins/security/userfiledao/1.0/UserFileBean.php',
  'UserFileDaoException' => 'mouf/../plugins/security/userfiledao/1.0/UserFileDaoException.php',
  'UserFileDao' => 'mouf/../plugins/security/userfiledao/1.0/UserFileDao.php',
  'FileCache' => 'mouf/../plugins/utils/cache/file-cache/1.1/FileCache.php',
  'TopSliderMenuRenderer' => 'mouf/../plugins/html/template/menus/topslidermenu/1.0/TopSliderMenuRenderer.php',
  'HtmlMenuTopSliderHead' => 'mouf/../plugins/html/template/menus/topslidermenu/1.0/HtmlMenuTopSliderHead.php',
  'ValidatorException' => 'mouf/../plugins/utils/common/validators/1.0/ValidatorException.php',
  'AbstractValidator' => 'mouf/../plugins/utils/common/validators/1.0/AbstractValidator.php',
  'EmailValidator' => 'mouf/../plugins/utils/common/validators/1.0/EmailValidator.php',
  'NumberValidator' => 'mouf/../plugins/utils/common/validators/1.0/NumberValidator.php',
  'FilterUtils' => 'mouf/../plugins/mvc/splash-common/3.2/services/FilterUtils.php',
  'SplashUtils' => 'mouf/../plugins/mvc/splash-common/3.2/services/SplashUtils.php',
  'AbstractFilter' => 'mouf/../plugins/mvc/splash-common/3.2/filters/AbstractFilter.php',
  'RequireHttpsAnnotation' => 'mouf/../plugins/mvc/splash-common/3.2/filters/RequireHttpsAnnotation.php',
  'RedirectToHttpAnnotation' => 'mouf/../plugins/mvc/splash-common/3.2/filters/RedirectToHttpAnnotation.php',
  'XajaAnnotation' => 'mouf/../plugins/mvc/splash-common/3.2/filters/XajaAnnotation.php',
  'ApplicationException' => 'mouf/../plugins/mvc/splash-common/3.2/utils/ApplicationException.php',
  'AnnotationException' => 'mouf/../plugins/mvc/splash-common/3.2/utils/annotations/AnnotationException.php',
  'varAnnotation' => 'mouf/annotations/varAnnotation.php',
  'paramAnnotation' => 'mouf/annotations/paramAnnotation.php',
  'URLAnnotation' => 'mouf/../plugins/mvc/splash-common/3.2/utils/annotations/URLAnnotation.php',
  'TitleAnnotation' => 'mouf/../plugins/mvc/splash-common/3.2/utils/annotations/TitleAnnotation.php',
  'ParamAnnotationAnalyzer' => 'mouf/../plugins/mvc/splash-common/3.2/utils/ParamAnnotationAnalyzer.php',
  'ExceptionUtils' => 'mouf/../plugins/mvc/splash-common/3.2/utils/ExceptionUtils.php',
  'Splash' => 'mouf/../plugins/mvc/splash/3.2/models/Splash.php',
  'WebService' => 'mouf/../plugins/mvc/splash/3.2/models/Splash.php',
  'SplashAction' => 'mouf/../plugins/mvc/splash/3.2/models/Splash.php',
  'SimpleLoginController' => 'mouf/../plugins/security/simplelogincontroller/1.0/SimpleLoginController.php',
  'LoggedAnnotation' => 'mouf/../plugins/security/userservice-splash/3.0/Logged.php',
  'ScriptTagWidget' => 'mouf/../plugins/html/widgets/scripttagwidget/1.0/ScriptTagWidget.php',
  'MoufTemplate' => 'mouf/../plugins/html/template/MoufTemplate/1.0/MoufTemplate.php',
  'GrayMenu' => 'mouf/../plugins/html/template/MoufTemplate/1.0/MoufMenu.php',
  'GrayMenuItem' => 'mouf/../plugins/html/template/MoufTemplate/1.0/MoufMenuItem.php',
  'TopRibbonMenuRenderer' => 'mouf/../plugins/html/template/menus/topribbonmenu/1.0/TopRibbonMenuRenderer.php',
  'HtmlMenuTopRibbonHead' => 'mouf/../plugins/html/template/menus/topribbonmenu/1.0/HtmlMenuTopRibbonHead.php',
  'HtmlJSSyntaxHighlighter' => 'mouf/../plugins/javascript/syntaxhighlighter/3.0.83/HtmlJSSyntaxHighlighter.php',
  'LogInterface' => 'mouf/../plugins/utils/log/log_interface/1.1/LogInterface.php',
  'ConditionInterface' => 'mouf/../plugins/utils/common/conditioninterface/1.0/ConditionInterface.php',
  'Scopable' => 'mouf/../plugins/html/htmlelement/1.0/Scopable.php',
  'HtmlElementInterface' => 'mouf/../plugins/html/htmlelement/1.0/HtmlElementInterface.php',
  'TemplateInterface' => 'mouf/../plugins/html/template/BaseTemplate/1.0/TemplateInterface.php',
  'LanguageDetectionInterface' => 'mouf/../plugins/utils/i18n/fine/2.1/language/LanguageDetectionInterface.php',
  'LanguageTranslationInterface' => 'mouf/../plugins/utils/i18n/fine/2.1/translate/LanguageTranslationInterface.php',
  'MenuInterface' => 'mouf/../plugins/html/widgets/menu/1.0/MenuInterface.php',
  'MenuItemInterface' => 'mouf/../plugins/html/widgets/menu/1.0/MenuItemInterface.php',
  'MenuItemStyleInterface' => 'mouf/../plugins/html/widgets/menu/1.0/MenuItemStyleInterface.php',
  'AuthenticationListenerInterface' => 'mouf/../plugins/security/userservice/1.0/AuthenticationListenerInterface.php',
  'UserInterface' => 'mouf/../plugins/security/userservice/1.0/UserInterface.php',
  'UserWithMailInterface' => 'mouf/../plugins/security/userservice/1.0/UserWithMailInterface.php',
  'UserDaoInterface' => 'mouf/../plugins/security/userservice/1.0/UserDaoInterface.php',
  'UserServiceInterface' => 'mouf/../plugins/security/userservice/1.0/UserServiceInterface.php',
  'CacheInterface' => 'mouf/../plugins/utils/cache/cache-interface/1.0/CacheInterface.php',
  'ValidatorInterface' => 'mouf/../plugins/utils/common/validators/1.0/ValidatorInterface.php',
  'UrlProviderInterface' => 'mouf/../plugins/mvc/splash-common/3.2/services/UrlProviderInterface.php',
  'WebServiceInterface' => 'mouf/../plugins/mvc/splash/3.2/models/Splash.php',
  'MoufValidatorService' => 'mouf/validator/MoufValidatorService.php',
  'MoufBasicValidationProvider' => 'mouf/validator/MoufBasicValidationProvider.php',
  'MoufSearchService' => 'mouf/MoufSearchService.php',
  'MoufActionDescriptor' => 'mouf/actions/MoufActionDescriptor.php',
  'DownloadPackageAction' => 'mouf/actions/DownloadPackageAction.php',
  'EnablePackageAction' => 'mouf/actions/EnablePackageAction.php',
  'RedirectAction' => 'mouf/actions/RedirectAction.php',
  'MultiStepActionService' => 'mouf/actions/MultiStepActionService.php',
  'InstallController' => 'mouf/actions/InstallController.php',
  'MoufActionRedirectResult' => 'mouf/actions/MoufActionRedirectResult.php',
  'MoufActionDoneResult' => 'mouf/actions/MoufActionDoneResult.php',
  'Moufspector' => 'mouf/controllers/MoufController.php',
  'MoufPropertyDescriptor' => 'mouf/MoufPropertyDescriptor.php',
  'OneOfAnnotation' => 'mouf/controllers/MoufController.php',
  'MoufAnnotationHelper' => 'mouf/controllers/MoufController.php',
  'OneOfTextAnnotation' => 'mouf/controllers/MoufController.php',
  'ExtendedActionAnnotation' => 'mouf/controllers/MoufController.php',
  'MoufController' => 'mouf/controllers/MoufController.php',
  'MoufRootController' => 'mouf/controllers/MoufRootController.php',
  'ComponentsController' => 'mouf/controllers/ComponentsController.php',
  'PackageController' => 'mouf/controllers/PackageController.php',
  'AbstractMoufInstanceController' => 'mouf/controllers/MoufInstanceController.php',
  'MoufInstanceController' => 'mouf/controllers/MoufInstanceController.php',
  'MoufAjaxInstanceController' => 'mouf/controllers/MoufAjaxInstanceController.php',
  'MoufDisplayGraphController' => 'mouf/controllers/MoufDisplayGraphController.php',
  'ConfigController' => 'mouf/controllers/ConfigController.php',
  'MoufValidatorController' => 'mouf/controllers/MoufValidatorController.php',
  'MoufLoginController' => 'mouf/controllers/MoufLoginController.php',
  'PackageServiceController' => 'mouf/controllers/PackageServiceController.php',
  'RepositorySourceController' => 'mouf/controllers/RepositorySourceController.php',
  'PackageDownloadController' => 'mouf/controllers/PackageDownloadController.php',
  'DocumentationController' => 'mouf/controllers/DocumentationController.php',
  'MoufPackageDownloadService' => 'mouf/MoufPackageDownloadService.php',
  'MoufNetworkException' => 'mouf/MoufNetworkException.php',
  'MoufRepository' => 'mouf/MoufRepository.php',
  'MoufUtils' => 'mouf/MoufUtils.php',
  'PhpInfoController' => 'mouf/controllers/PhpInfoController.php',
  'SearchController' => 'mouf/controllers/SearchController.php',
  'InstallUtils' => 'mouf/actions/InstallUtils.php',
  'MoufValidationProviderInterface' => 'mouf/validator/MoufBasicValidationProvider.php',
  'DisplayPackageListInterface' => 'mouf/controllers/DisplayPackageListInterface.php',
  'MoufSearchable' => 'mouf/MoufSearchable.php',
  'MoufActionProviderInterface' => 'mouf/actions/MoufActionProvider.php',
  'MoufActionResultInteface' => 'mouf/actions/MoufActionResultInterface.php',
  'MoufInstanceDescriptor' => 'mouf/MoufInstanceDescriptor.php',
  'MoufInstancePropertyDescriptor' => 'mouf/MoufInstancePropertyDescriptor.php',
));
spl_autoload_register(array(MoufManager::getMoufManager(), "autoload"));
// Packages dependencies
$localFilePath = dirname(__FILE__);
require_once $localFilePath.'/../plugins/utils/common/getvars/1.1/tcm_utils.php';
require_once $localFilePath.'/../plugins/utils/common/getvars/1.1/TcmUtilsException.php';
require_once $localFilePath.'/../plugins/utils/log/log_interface/1.1/LogInterface.php';
require_once $localFilePath.'/../plugins/utils/common/conditioninterface/1.0/ConditionInterface.php';
require_once $localFilePath.'/../plugins/utils/common/conditioninterface/1.0/AndCondition.php';
require_once $localFilePath.'/../plugins/utils/common/conditioninterface/1.0/OrCondition.php';
require_once $localFilePath.'/../plugins/utils/common/conditioninterface/1.0/NotCondition.php';
require_once $localFilePath.'/../plugins/utils/common/conditioninterface/1.0/TrueCondition.php';
require_once $localFilePath.'/../plugins/utils/common/conditioninterface/1.0/FalseCondition.php';
require_once $localFilePath.'/../plugins/html/htmlelement/1.0/Scopable.php';
require_once $localFilePath.'/../plugins/html/htmlelement/1.0/HtmlElementInterface.php';
require_once $localFilePath.'/../plugins/html/htmlelement/1.0/HtmlFromFile.php';
require_once $localFilePath.'/../plugins/html/htmlelement/1.0/HtmlFromFunction.php';
require_once $localFilePath.'/../plugins/html/htmlelement/1.0/HtmlString.php';
require_once $localFilePath.'/../plugins/utils/export/phpexcel/1.7.5/PHPExcel.php';
require_once $localFilePath.'/../plugins/html/template/BaseTemplate/1.0/TemplateInterface.php';
require_once $localFilePath.'/../plugins/html/template/BaseTemplate/1.0/BaseTemplate.php';
require_once $localFilePath.'/../plugins/javascript/jquery/jquery/1.6/HtmlJSJQuery.php';
require_once $localFilePath.'/../plugins/javascript/jquery/jquery-fixedheadertable/1.3/HtmlJSJQueryFixedHeaderTable.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/FineMessageLanguage.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/language/LanguageDetectionInterface.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/language/BrowserLanguageDetection.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/language/DomainLanguageDetection.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/language/FixedLanguageDetection.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/language/CascadingLanguageDetection.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/translate/LanguageTranslationInterface.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/translate/FinePHPArrayTranslationService.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/misc/FineCurrencyUtils.php';
require_once $localFilePath.'/../plugins/utils/i18n/fine/2.1/msgFunctions.php';
require_once $localFilePath.'/../plugins/html/widgets/menu/1.0/MenuInterface.php';
require_once $localFilePath.'/../plugins/html/widgets/menu/1.0/MenuItemInterface.php';
require_once $localFilePath.'/../plugins/html/widgets/menu/1.0/MenuItemStyleInterface.php';
require_once $localFilePath.'/../plugins/html/widgets/menu/1.0/Menu.php';
require_once $localFilePath.'/../plugins/html/widgets/menu/1.0/MenuItem.php';
require_once $localFilePath.'/../plugins/html/widgets/menu/1.0/MenuItemStyleIcon.php';
require_once $localFilePath.'/../plugins/html/template/SplashTemplate/2.0/SplashTemplate.php';
require_once $localFilePath.'/../plugins/html/template/SplashTemplate/2.0/SplashMenuRenderer.php';
require_once $localFilePath.'/../plugins/javascript/jquery/jquery-autogrow/1.2.2/HtmlJSJQueryAutoGrow.php';
require_once $localFilePath.'/../plugins/javascript/jquery/jquery-ui/1.7.2/HtmlJSJQueryUI.php';
require_once $localFilePath.'/../plugins/javascript/jquery/jqueryFileTree/1.01/HtmlJSJQueryFileTree.php';
require_once $localFilePath.'/../plugins/javascript/jit/1.1.2/HtmlJSJit.php';
require_once $localFilePath.'/../plugins/javascript/prototype/1.6.0.1/HtmlJSPrototype.php';
require_once $localFilePath.'/../plugins/utils/log/errorlog_logger/1.1/ErrorLogLogger.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/UserDaoException.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/UserServiceException.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/AuthenticationListenerInterface.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/UserInterface.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/UserWithMailInterface.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/UserDaoInterface.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/UserServiceInterface.php';
require_once $localFilePath.'/../plugins/security/userservice/1.0/MoufUserService.php';
require_once $localFilePath.'/../plugins/security/userfiledao/1.0/UserFileBean.php';
require_once $localFilePath.'/../plugins/security/userfiledao/1.0/UserFileDaoException.php';
require_once $localFilePath.'/../plugins/security/userfiledao/1.0/UserFileDao.php';
require_once $localFilePath.'/../plugins/utils/cache/cache-interface/1.0/CacheInterface.php';
require_once $localFilePath.'/../plugins/utils/cache/file-cache/1.1/FileCache.php';
require_once $localFilePath.'/../plugins/html/template/menus/topslidermenu/1.0/TopSliderMenuRenderer.php';
require_once $localFilePath.'/../plugins/html/template/menus/topslidermenu/1.0/HtmlMenuTopSliderHead.php';
require_once $localFilePath.'/../plugins/utils/common/validators/1.0/ValidatorInterface.php';
require_once $localFilePath.'/../plugins/utils/common/validators/1.0/ValidatorException.php';
require_once $localFilePath.'/../plugins/utils/common/validators/1.0/AbstractValidator.php';
require_once $localFilePath.'/../plugins/utils/common/validators/1.0/EmailValidator.php';
require_once $localFilePath.'/../plugins/utils/common/validators/1.0/NumberValidator.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/load.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/services/FilterUtils.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/services/SplashUtils.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/services/UrlProviderInterface.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/controllers/Controller.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/filters/AbstractFilter.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/filters/RequireHttpsAnnotation.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/filters/RedirectToHttpAnnotation.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/filters/XajaAnnotation.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/ApplicationException.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/annotations/AnnotationException.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/annotations/paramAnnotation.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/annotations/URLAnnotation.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/annotations/TitleAnnotation.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/ParamAnnotationAnalyzer.php';
require_once $localFilePath.'/../plugins/mvc/splash-common/3.2/utils/ExceptionUtils.php';
require_once $localFilePath.'/../plugins/mvc/splash/3.2/models/Splash.php';
require_once $localFilePath.'/../plugins/security/simplelogincontroller/1.0/SimpleLoginController.php';
require_once $localFilePath.'/../plugins/security/userservice-splash/3.0/Logged.php';
require_once $localFilePath.'/../plugins/html/widgets/scripttagwidget/1.0/ScriptTagWidget.php';
require_once $localFilePath.'/../plugins/html/template/MoufTemplate/1.0/MoufTemplate.php';
require_once $localFilePath.'/../plugins/html/template/MoufTemplate/1.0/MoufMenu.php';
require_once $localFilePath.'/../plugins/html/template/MoufTemplate/1.0/MoufMenuItem.php';
require_once $localFilePath.'/../plugins/html/template/menus/topribbonmenu/1.0/TopRibbonMenuRenderer.php';
require_once $localFilePath.'/../plugins/html/template/menus/topribbonmenu/1.0/HtmlMenuTopRibbonHead.php';
require_once $localFilePath.'/../plugins/javascript/syntaxhighlighter/3.0.83/HtmlJSSyntaxHighlighter.php';

// User dependencies
require_once $localFilePath.'/validator/MoufValidatorService.php';
require_once $localFilePath.'/validator/MoufBasicValidationProvider.php';
require_once $localFilePath.'/controllers/DisplayPackageListInterface.php';
require_once $localFilePath.'/MoufSearchable.php';
require_once $localFilePath.'/MoufSearchService.php';
require_once $localFilePath.'/actions/MoufActionDescriptor.php';
require_once $localFilePath.'/actions/MoufActionProvider.php';
require_once $localFilePath.'/actions/DownloadPackageAction.php';
require_once $localFilePath.'/actions/EnablePackageAction.php';
require_once $localFilePath.'/actions/RedirectAction.php';
require_once $localFilePath.'/actions/MultiStepActionService.php';
require_once $localFilePath.'/actions/InstallController.php';
require_once $localFilePath.'/actions/MoufActionResultInterface.php';
require_once $localFilePath.'/actions/MoufActionRedirectResult.php';
require_once $localFilePath.'/actions/MoufActionDoneResult.php';
require_once $localFilePath.'/controllers/MoufController.php';
require_once $localFilePath.'/controllers/MoufRootController.php';
require_once $localFilePath.'/controllers/ComponentsController.php';
require_once $localFilePath.'/controllers/PackageController.php';
require_once $localFilePath.'/controllers/MoufInstanceController.php';
require_once $localFilePath.'/controllers/MoufAjaxInstanceController.php';
require_once $localFilePath.'/controllers/MoufDisplayGraphController.php';
require_once $localFilePath.'/controllers/ConfigController.php';
require_once $localFilePath.'/controllers/MoufValidatorController.php';
require_once $localFilePath.'/controllers/MoufLoginController.php';
require_once $localFilePath.'/controllers/PackageServiceController.php';
require_once $localFilePath.'/controllers/RepositorySourceController.php';
require_once $localFilePath.'/controllers/PackageDownloadController.php';
require_once $localFilePath.'/controllers/DocumentationController.php';
require_once $localFilePath.'/MoufPackageDownloadService.php';
require_once $localFilePath.'/MoufNetworkException.php';
require_once $localFilePath.'/MoufRepository.php';
require_once $localFilePath.'/MoufUtils.php';
require_once $localFilePath.'/controllers/PhpInfoController.php';
require_once $localFilePath.'/controllers/SearchController.php';
require_once $localFilePath.'/actions/InstallUtils.php';
require_once $localFilePath.'/load.php';

?>
