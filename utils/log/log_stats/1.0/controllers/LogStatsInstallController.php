<?php

/**
 * The controller used in the LogStats install process.
 * 
 * @Component
 * @Logged
 */
class LogStatsInstallController extends Controller {
	
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

	protected $tableName;
	
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
		
		$this->tableName = "logstats";
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep2.php", $this);
		$this->template->draw();
	}
	
	/**
	 * This action generates the LogStats instance, and creates the table. 
	 * 
	 * @Action
	 * @param string $dblogger
	 * @param string $tablename
	 * @param string $selfedit
	 */
	public function install($dblogger, $tablename, $selfedit="false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		//$this->createTable($dbconnection, $tablename, $selfedit);
		
		
		
		// ********* TIME DIMENSION ***********
		$yearColumnName = $tablename."_stats_timeDimension_yearColumn";
		if (!$this->moufManager->instanceExists($yearColumnName)) {
			$this->moufManager->declareComponent($yearColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($yearColumnName, "columnName", "year");
			$this->moufManager->setParameter($yearColumnName, "dataOrigin", "YEAR([statcol].log_date)");
			$this->moufManager->setParameter($yearColumnName, "type", "INT");
		}
		
		$monthColumnName = $tablename."_stats_timeDimension_monthColumn";
		if (!$this->moufManager->instanceExists($monthColumnName)) {
			$this->moufManager->declareComponent($monthColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($monthColumnName, "columnName", "month");
			$this->moufManager->setParameter($monthColumnName, "dataOrigin", "MONTH([statcol].log_date)");
			$this->moufManager->setParameter($monthColumnName, "type", "INT");
		}
		
		$dayColumnName = $tablename."_stats_timeDimension_dayColumn";
		if (!$this->moufManager->instanceExists($dayColumnName)) {
			$this->moufManager->declareComponent($dayColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($dayColumnName, "columnName", "day");
			$this->moufManager->setParameter($dayColumnName, "dataOrigin", "DAY([statcol].log_date)");
			$this->moufManager->setParameter($dayColumnName, "type", "INT");
		}
		
		$hourColumnName = $tablename."_stats_timeDimension_hourColumn";
		if (!$this->moufManager->instanceExists($hourColumnName)) {
			$this->moufManager->declareComponent($hourColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($hourColumnName, "columnName", "hour");
			$this->moufManager->setParameter($hourColumnName, "dataOrigin", "HOUR([statcol].log_date)");
			$this->moufManager->setParameter($hourColumnName, "type", "INT");
		}
		
		$timeColumns = array();
		$timeColumns[] = $yearColumnName;
		$timeColumns[] = $monthColumnName;
		$timeColumns[] = $dayColumnName;
		$timeColumns[] = $hourColumnName;
		
		$timeDimensionName = $tablename."_stats_timeDimension";
		if (!$this->moufManager->instanceExists($timeDimensionName)) {
			$this->moufManager->declareComponent($timeDimensionName, "DB_Dimension");
			$this->moufManager->bindComponents($timeDimensionName, "columns", $timeColumns);
		}
		
		// ********* CATEGORY DIMENSION ***********
		$category1ColumnName = $tablename."_stats_categoryDimension_category1Column";
		if (!$this->moufManager->instanceExists($category1ColumnName)) {
			$this->moufManager->declareComponent($category1ColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($category1ColumnName, "columnName", "category1");
			$this->moufManager->setParameter($category1ColumnName, "dataOrigin", "[statcol].category1");
			$this->moufManager->setParameter($category1ColumnName, "type", "VARCHAR(30)");
		}
		
		$category2ColumnName = $tablename."_stats_categoryDimension_category2Column";
		if (!$this->moufManager->instanceExists($category2ColumnName)) {
			$this->moufManager->declareComponent($category2ColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($category2ColumnName, "columnName", "category2");
			$this->moufManager->setParameter($category2ColumnName, "dataOrigin", "[statcol].category2");
			$this->moufManager->setParameter($category2ColumnName, "type", "VARCHAR(30)");
		}
		
		$category3ColumnName = $tablename."_stats_categoryDimension_category3Column";
		if (!$this->moufManager->instanceExists($category3ColumnName)) {
			$this->moufManager->declareComponent($category3ColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($category3ColumnName, "columnName", "category3");
			$this->moufManager->setParameter($category3ColumnName, "dataOrigin", "[statcol].category3");
			$this->moufManager->setParameter($category3ColumnName, "type", "VARCHAR(30)");
		}
		
		$categoryColumns = array();
		$categoryColumns[] = $category1ColumnName;
		$categoryColumns[] = $category2ColumnName;
		$categoryColumns[] = $category3ColumnName;
		
		$categoryDimensionName = $tablename."_stats_categoryDimension";
		if (!$this->moufManager->instanceExists($categoryDimensionName)) {
			$this->moufManager->declareComponent($categoryDimensionName, "DB_Dimension");
			$this->moufManager->bindComponents($categoryDimensionName, "columns", $categoryColumns);
		}
		
		// ********* LOGLEVEL DIMENSION ***********
		$logLevelColumnName = $tablename."_stats_categoryDimension_logLevelColumn";
		if (!$this->moufManager->instanceExists($logLevelColumnName)) {
			$this->moufManager->declareComponent($logLevelColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($logLevelColumnName, "columnName", "log_level");
			$this->moufManager->setParameter($logLevelColumnName, "dataOrigin", "[statcol].log_level");
			$this->moufManager->setParameter($logLevelColumnName, "type", "VARCHAR(8)");
		}
		
		$logLevelDimensionName = $tablename."_stats_logLevelDimension";
		if (!$this->moufManager->instanceExists($logLevelDimensionName)) {
			$this->moufManager->declareComponent($logLevelDimensionName, "DB_Dimension");
			$this->moufManager->bindComponents($logLevelDimensionName, "columns", array($logLevelColumnName));
		}
		
		$dimensions = array($timeDimensionName, $categoryDimensionName, $logLevelDimensionName); 
		
		// ************************* VALUES ****************
		$countColumnName = $tablename."_stats_countColumn";
		if (!$this->moufManager->instanceExists($countColumnName)) {
			$this->moufManager->declareComponent($countColumnName, "DB_StatColumn");
			$this->moufManager->setParameter($countColumnName, "columnName", "nb_logs");
			$this->moufManager->setParameter($countColumnName, "dataOrigin", "1");
			$this->moufManager->setParameter($countColumnName, "type", "INT");
		}
		
		
		$logStatsName = $tablename."_log_stats";
		if (!$this->moufManager->instanceExists($logStatsName)) {
			$this->moufManager->declareComponent($logStatsName, "DB_Stats");
			// Use the same dbConnection as the DBLogger
			$this->moufManager->bindComponent($logStatsName, "dbConnection", $this->moufManager->getBoundComponentsOnProperty($dblogger, "dbConnection"));
			$this->moufManager->setParameter($logStatsName, "sourceTable", $this->moufManager->getParameter($dblogger, "tableName"));
			$this->moufManager->setParameter($logStatsName, "statsTable", $tablename);
			$this->moufManager->bindComponents($logStatsName, "dimensions", $dimensions);
			$this->moufManager->bindComponents($logStatsName, "values", array($countColumnName)); 
		}
		
		$this->moufManager->rewriteMouf();

		MoufProxy::getInstance($logStatsName)->createStatsTable(true);
		MoufProxy::getInstance($logStatsName)->createTrigger();
		MoufProxy::getInstance($logStatsName)->fillTable();

		InstallUtils::continueInstall($selfedit == "true");
	}
	
	protected $errorMsg;
	
	private function displayErrorMsg($msg) {
		$this->errorMsg = $msg;
		$this->template->addContentFile(dirname(__FILE__)."/../views/installError.php", $this);
		$this->template->draw();
	}
	
}