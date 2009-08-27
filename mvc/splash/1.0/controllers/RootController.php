<?php
require_once CONTROLLERS_PATHS."WelcomeController.php";
//require_once CONTROLLERS_PATHS."StatsClientController.php";

class RootController extends Controller {

//	/**
//	 * @Action
//	 */
//	function defaultAction() {
//		if(!SessionUtils::isLogged())
//		{
//			$welcomeController = new WelcomeController();
//			$welcomeController->callAction("defaultAction");
//		}else {
//			$statsController = new StatsClientController();
//			$statsController->callAction("defaultAction");
//		}
//	}


	//Called if the users want to be redirected to the forum after login...
	function toForumAction(){
		require_once CONTROLLERS_PATHS."WelcomeController.php";
		$WelcomeController = new WelcomeController();
		$WelcomeController->LoginPage(FORUM_URL);
	}
	
	
	/**
	 * @Action
	 * @RequireHttps("redirect")
	 */
	public function defaultAction() {
		//header("Location:".ROOT_URL."StatsClient/");
		//exit;
		$this->LoginPage(ROOT_URL."Welcome/");
	}

	public function LoginPage($redirectUrl=null){
		$template = $this->getTemplate();
		$template->addContentFunction("welcome", $redirectUrl)
				 ->addJsFile(ROOT_URL."include/script/Validator.js")
				 ->addHeadFunction("jsWelcome")
				 ->addHeadFunction("scriptEffect")
				 ->draw();
	}


	/**
	 * @Action
	 * @Var{email}(origin="request[forgotEmail]", validator="Email")
	 */
	public function sendForgotPassword($email){
		$user = UserService::getUserByEmail($email);
		if ($user==null){
			$message = 'no.user.for.email';
		}else{
			$success = MailUtils::sendForgotPasswordEmail($user);
			$message = $success?'success':'fail';
		}
		MessageController::redirectToMessage('lost.pwd.title', "lost.pwd.".$message);
	}

}
?>
