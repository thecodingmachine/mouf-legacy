<?php

/**
 * A simple controller that provides basic login features.
 * Very useful to get quickly started, although you might want to develop your own or extend it to add custom features.
 * 
 * @author David
 * @Component
 */
class SimpleLoginController extends Controller {
	
	/**
	 * The template to use to display.
	 * 
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * The user service
	 *
	 * @Property
	 * @Compulsory
	 * @var MoufUserService
	 */
	public $userService;
	
	/**
	 * Unless a redirecturl parameter is passed in the request, after login, the user will be redirected to this URL.
	 * It is relative to the ROOT_URL and should NOT start with a "/".
	 * 
	 * @Property
	 * @var string
	 */
	public $defaultRedirectUrl;

	/**
	 * The URL to redirect to on logout.
	 * It is relative to the ROOT_URL and should NOT start with a "/".
	 * 
	 * @Property
	 * @var string
	 */
	public $logoutRedirectUrl;
	
	/**
	 * The label for the "login" field.
	 * 
	 * @Property
	 * @var string
	 */
	public $loginLabel = "Login";

	/**
	 * Whether the label for the "login" field should be internationalized or not.
	 * 
	 * @Property
	 * @var boolean
	 */
	public $i18nLoginLabel = false;
	
	/**
	 * The label for the "password" field.
	 * 
	 * @Property
	 * @var string
	 */
	public $passwordLabel = "Password";

	/**
	 * Whether the label for the "password" field should be internationalized or not.
	 * 
	 * @Property
	 * @var boolean
	 */
	public $i18nPasswordLabel = false;
	
	/**
	 * The label for the "login" submit button.
	 * 
	 * @Property
	 * @var string
	 */
	public $loginSubmitLabel = "Login";

	/**
	 * Whether the label for the "login" submit button should be internationalized or not.
	 * 
	 * @Property
	 * @var boolean
	 */
	public $i18nLoginSubmitLabel = false;
	
	/**
	 * The label for the error message if login credentials are wrong.
	 * 
	 * @Property
	 * @var string
	 */
	public $badCredentialsLabel = "Invalid login or password, please try again.";

	/**
	 * Whether the label for the error message should be internationalized or not.
	 * 
	 * @Property
	 * @var boolean
	 */
	public $i18nBadCredentialsLabel = false;
	
	/**
	 * The HTML elements that will be displayed before the login box.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $contentBeforeLoginBox;
	
	/**
	 * The HTML elements that will be displayed after the login box.
	 *
	 * @Property
	 * @var array<HtmlElementInterface>
	 */
	public $contentAfterLoginBox;
	
	
	
	/// Whether we should display an error message or not for bad credentials.
	protected $badCredentials;
	
	protected $userDisabled;
	
	protected $login;
	
	protected $redirecturl;
	
	/**
	 * The index page will display the login form.
	 * 
	 * @Action
	 * @param string $login The login to fill by default.
	 * @param string $redirecturl The URL to redirect to when login is done. If not specified, the default login URL defined in the controller will be used instead.
	 */
	public function defaultAction($login = null, $redirect = null) {
		$this->redirecturl = $redirect;
		$this->login = $login;
		
		$this->template->setTitle(iMsg("login.title"));
		$this->template->addContentFile(dirname(__FILE__)."/views/login.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Logs the user in.
	 *
	 * @Action
	 * @param string $login
	 * @param string $password
	 * @param string $redirect
	 */
	public function login($login, $password, $redirect = null) {
		$result = $this->userService->login($login, $password);
		if ($result == false) {
			// Access forbidden:
			header('HTTP/1.1 403 Forbidden');

			$this->badCredentials = true;
			$this->defaultAction($login, $redirect);
			return;
		} else {
			if (!empty($redirect)) {
				header('Location: '.$redirect);
				return;
			} else {
				header('Location: '.ROOT_URL.$this->defaultRedirectUrl);
				return;
			}
		}
	}
	
	/**
	 * Logs the user out.
	 *
	 * @Action
	 */
	public function logout() {
		$this->userService->logoff();
		header("Location: ".ROOT_URL.$this->logoutRedirectUrl);
	}
	
	/**
	 * This function draws an array like $left, or $content.
	 * Those arrays can contain text to draw or function to call.
	 */
	protected function drawArray($array) {
		if (is_array($array)) {
			foreach ($array as $element) {
				$element->toHtml();
			}
		}
	}
	
}