<?php 

/**
 * The controller that will write the .htaccess file.
 *
 * @Component
 */
class SplashAdminApacheConfigureController extends Controller {

	/**
	 * The template used by the Splash page.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Displays the config page. 
	 *
	 * @Action
	 */
	public function defaultAction() {
		$this->template->addContentFile(dirname(__FILE__)."/../../views/admin/splashAdminApache.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Writes the .htaccess file. 
	 *
	 * @Action
	 */
	public function write($selfedit) {
		$uri = $_SERVER["REQUEST_URI"];
		
		$installPos = strpos($uri, "/mouf/splashApacheConfig/write");
		if ($installPos !== FALSE) {
			$uri = substr($uri, 0, $installPos);
		}
		if (empty($uri)) {
			$uri = "/";
		}
		
		
		$str = "Options FollowSymLinks
		RewriteEngine on
		RewriteBase $uri
		
		#RewriteCond %{REQUEST_FILENAME} !-f
		#RewriteCond %{REQUEST_FILENAME} !-d
		
		RewriteRule !((\.(js|ico|gif|jpg|png|css)$)|^plugins|^mouf) plugins/mvc/splash/3.0/splash.php";
		
		file_put_contents(dirname(__FILE__)."/../../../../../../.htaccess", $str);
		//var_dump("Location: ".ROOT_URL."mouf/?selfedit=".$selfedit);
		header("Location: ".ROOT_URL."mouf/?selfedit=".$selfedit);
	}
}

?>