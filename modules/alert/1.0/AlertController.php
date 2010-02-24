<?php
/**
 * This controller lists alerts and shows the detail for an alert.
 *
 * @Component
 */
class AlertController extends Controller {  
	
	/**
	 * The template to use by the controller.
	 * 
	 * @Property
	 * @compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * The grid listing the alerts.
	 *
	 * @Property
	 * @compulsory
	 * @var JqGridWidget
	 */
	public $alertsGrid;

	/**
	 * The service for accessing the current user.
	 *
	 * @Property
	 * @compulsory
	 * @var UserServiceInterface
	 */
	public $userService;
	
	/**
	 * The alert Dao 
	 *
	 * @Property
	 * @compulsory
	 * @var AlertDaoInterface
	 */
	public $alertsDao;
	
	public $alertBean;
	
	/**
	 * This action lists the alerts.
	 *
	 * @Action
	 */
	public function defaultAction() {
		$this->template->addContentFile(dirname(__FILE__).'/views/index.php', $this);
		$this->template->addHeadText("<script src='".ROOT_URL."plugins/modules/alert/1.0/views/alerts.js'></script>");
		$this->template->draw();
	}
	
	/**
	 * Returns the XML containing the alerts list
	 *
	 * @Action
	 */
	public function alertsList($page, $rows, $sidx, $sord) {
		$user = $this->userService->getLoggedUser();
		
		$this->alertsGrid->addDatasourceParam("user_id", $user->getId());
		$this->alertsGrid->printXmlData($page, $rows, $sidx, $sord);
	}
	
	public static function checkboxFormatter($row) {
		/*$checked = ($row->validated)?"checked='checked'":"";
		return "<input type='checkbox' value='on' $checked onclick='validateAlert($row->id)'></input>";*/
		return "<input type='checkbox' value='on' onclick='validateAlert($row->id)'></input>";
	}
	
	/**
	 * Validates the alert (called directly via Ajax from the list, or via the validate Action).
	 *
	 * @Action
	 * @param int $id
	 */
	public function validateAlert($id) {
		$alertBean = $this->alertsDao->getAlertById($id);
		$alertBean->setValidated(true);
		$alertBean->save();
	}
	
	/**
	 * Validates the alert (called via Ajax from the list).
	 *
	 * @Action
	 * @param int $id
	 * @param string $action
	 */
	public function validate($id, $action) {
		if ($action == "validate") {
			$this->validateAlert($id);
		}
		header("Location: ".ROOT_URL."alerts/");
	}
	
	/**
	 * Validates all alerts at once.
	 *
	 * @Action
	 */
	public function validateAll() {
		$alerts = $this->alertsDao->getNonvalidatedAlerts();
		foreach ($alerts as $alert) {
			/* @var $alert AlertBean */
			$alert->setValidated(1);
			$alert->save();
		}
		header("Location: ".ROOT_URL."alerts/");
	}
	
	/**
	 * Prints the alerts detail screen.
	 *
	 * @Action
	 * @param int $id
	 */
	public function detail($id) {
		$this->alertBean = $this->alertsDao->getAlertById($id);
		$this->template->addContentFile(dirname(__FILE__).'/views/detail.php', $this);
		$this->template->draw();
	}
}
?>
