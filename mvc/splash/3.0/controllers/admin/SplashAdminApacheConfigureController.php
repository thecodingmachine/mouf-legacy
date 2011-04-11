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
	 * The service in charge of generating files.
	 * 
	 * @Property
	 * @Compulsory
	 * @var SplashGenerateService
	 */
	public $splashGenerateService;
	
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
		
		$this->splashGenerateService->writeHtAccess($uri);
		
		header("Location: ".ROOT_URL."mouf/?selfedit=".$selfedit);
	}
	
	
}

?>