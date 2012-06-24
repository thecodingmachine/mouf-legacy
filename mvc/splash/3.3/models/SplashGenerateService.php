<?php

/**
 * This class is in charge of all tasks that generate files:
 *  - .htaccess generation
 *  - controllers generation
 *  ...
 *  
 * @author david
 * @Component
 */
class SplashGenerateService {
	/**
	 * Writes the .htaccess file
	 * 
	 * @param string $rootUri
	 * @param array<string> $exludeExtentions
	 * @param array<string> $exludeFolders
	 */
	public function writeHtAccess($rootUri, $exludeExtentions, $exludeFolders) {

		$modelsDirName = dirname(__FILE__);
		$splashDir = dirname($modelsDirName);
		$splashVersion = basename($splashDir);
		
		$strExtentions = implode('|', $exludeExtentions);
		$strFolders = '^' . implode('|^', $exludeFolders);
		
		$str = "Options FollowSymLinks
		RewriteEngine on
		RewriteBase $rootUri
		
		#RewriteCond %{REQUEST_FILENAME} !-f
		#RewriteCond %{REQUEST_FILENAME} !-d
		
		RewriteRule !((\.($strExtentions)$)|$strFolders) plugins/mvc/splash/".$splashVersion."/splash.php";
		
		file_put_contents(dirname(__FILE__)."/../../../../../.htaccess", $str);
	}
	
	public function generateRootController($controllerDirectory, $viewDirectory) {
		
		$rootControllerStr = '<?php
/**
 * This controller has a special purpose since it is in charge of the root path of your web application.
 * This is because the name of the instance of this controller is "rootController".
 * For Splash, this is a special name that means it should be used as the main page of your web application.
 * 
 * @Component
 */
class RootController extends Controller {
	
	/**
	 * The template used by the main page.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Page displayed when a user arrives on your web application.
	 * 
	 * @URL /
	 */
	public function index() {
		$this->template->addContentFile(ROOT_PATH."'.$viewDirectory.'root/index.php", $this);
		$this->template->draw();
	}
}';
		mkdir(ROOT_PATH.$controllerDirectory, 0777, true);
		file_put_contents(ROOT_PATH.$controllerDirectory."/RootController.php", $rootControllerStr);
		chmod(ROOT_PATH.$controllerDirectory."/RootController.php", 0666);

		$indexViewStr = '<?php /* @var $this RootController */ ?>
<h1>Welcome to Splash</h1>

<p>This file is your welcome page. It is generated by the RootController class and the '.$viewDirectory.'root/index.php file. Please feel free to customize it.</p>';
		
		mkdir(ROOT_PATH.$viewDirectory."root", 0777, true);
		file_put_contents(ROOT_PATH.$viewDirectory."root/index.php", $indexViewStr);
		chmod(ROOT_PATH.$viewDirectory."root/index.php", 0666);		
	}
	
}