<?php

/**
 * The controller managing the install process.
 * It will create database tables and Mouf instances.
 *
 * @Component
 */
class BlackListMailServiceInstallController extends Controller  {
	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Displays the first install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
				
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep1.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Skips the install process.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function skip($selfedit = "false") {
		InstallUtils::continueInstall($selfedit == "true");
	}

	protected $datasourceInstanceName;
	
	/**
	 * Displays the second install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function configure($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->datasourceInstanceName = "datasource";
				
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep2.php", $this);
		$this->template->draw();
	}
	
	
	
	/**
	 * Action to set the database connection, create tables...
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function install($datasource, $selfedit = "false") {
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$moufManager = $this->moufManager;
		
		
		$datasourceProxy = MoufProxy::getInstance($datasource, $selfedit == "true");
		/* @var $datasourceProxy DB_ConnectionInterface */
		$datasourceProxy->executeSqlFile(dirname(__FILE__)."/../../database/install.sql", false);
		
		
		if (!$moufManager->instanceExists("blacklistMailService")) {
			$instanceDescriptor = $moufManager->createInstance("BlackListMailService");
			$instanceDescriptor->setName("blacklistMailService");
			$instanceDescriptor->getProperty("datasource")->setValue($moufManager->getInstanceDescriptor($datasource));
			if ($moufManager->instanceExists("dbMailService")) {
				$instanceDescriptor->getProperty("forwardTo")->setValue($moufManager->getInstanceDescriptor("dbMailService"));
			}
			
			if (!$moufManager->instanceExists("blacklistTranslationService")) {
				$languageTranslationInstanceDescriptor = $moufManager->createInstance("FinePHPArrayTranslationService");
				$languageTranslationInstanceDescriptor->setName("blacklistTranslationService");
				$languageTranslationInstanceDescriptor->getProperty("i18nMessagePath")->setValue("plugins/utils/mailer/blacklist-mail-service/1.0/resources/");
				$languageTranslationInstanceDescriptor->getProperty("languageDetection")->setValue($moufManager->getInstanceDescriptor("defaultLanguageDetection"));
				$instanceDescriptor->getProperty("languageTranslationService")->setValue($languageTranslationInstanceDescriptor);
			}
			$instanceDescriptor->getProperty("unsubscribeHtmlText")->setValue("blacklistmailservice.mail.htmltext");
			$instanceDescriptor->getProperty("unsubscribeText")->setValue("blacklistmailservice.mail.plaintext");
		}
		
		$moufManager->rewriteMouf();		
		
		InstallUtils::continueInstall($selfedit == "true");
	}
	
}