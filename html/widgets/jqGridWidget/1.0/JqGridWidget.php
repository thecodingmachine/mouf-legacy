<?php

/**
 * This class represent a jqGrid (a jQuery datagrid).
 * For your jqGrid to work, the jqGrid avascript file must be loaded in your page.
 *
 * @Component
 */
class JqGridWidget extends DataGrid implements HtmlElementInterface {
	
	private static $number = 0;
	
	/**
	 * The ID of the table element that will contain the table.
	 * If none is passed, an ID is autogenerated.
	 *
	 * @Property
	 * @var string
	 */
	public $tableId;
	
	/**
	 * The ID of the div element that will contian the pager.
	 * If none is passed, an ID is autogenerated.
	 *
	 * @Property
	 * @var string
	 */
	public $pagerId;
	
	/**
	 * The URL that will be used to retrieve the XML data to be displayed.
	 * Path is relative to the ROOT_URL and should not start with a /.
	 *
	 * @Property 
	 * @var string
	 */
	public $dataUrl;
	
	/**
	 * The name of the column which the default sort will be performed on.
	 *
	 * @Property 
	 * @Compulsory
	 * @var string
	 */
	public $defaultSortColumn;
	
	/**
	 * The default sort order (ASC or DESC).
	 *
	 * @Property
	 * @Compulsory
	 * @OneOf("ASC", "DESC")
	 * @var string
	 */
	public $defaultSortOrder = "ASC";
	
	/**
	 * The caption for the table.
	 * If none is passed, no caption is displayed.
	 *
	 * @Property
	 * @var string
	 */
	public $caption;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml() {
		if ($this->displayCondition != null && $this->displayCondition->isOk($this) == false) {
			return;
		}
		
		self::$number++; 
		
		if ($this->tableId == null) {
			$tableId = "moufJqGridTableNumber".self::$number;
		} else {
			$tableId = $this->tableId;
		}
		
		if ($this->pagerId == null) {
			$pagerId = "moufJqGridPagerNumber".self::$number;
		} else {
			$pagerId = $this->pagerId;
		}
		
		echo "<table id=\"".$tableId."\"></table>\n"; 
		echo "<div id=\"".$pagerId."\"></div>\n";
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo "<div><a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit table</a></div>\n";
			}
		}
		echo '<script type="text/javascript">
jQuery(document).ready(function(){';
		echo "
		jQuery('#".$tableId."').jqGrid({
    url:'".ROOT_URL.$this->dataUrl."',
    datatype: 'xml',
    mtype: 'GET',
    ".$this->getColumnsDefinition()."
    pager: '#".$pagerId."',
    rowNum:10,
    rowList:[10,20,30],
    sortname: '".$this->defaultSortColumn."',
    sortorder: '".$this->defaultSortOrder."',
    viewrecords: true,";
		if (!empty($this->caption)) {
    		echo "caption: '".$this->caption."',";
		}
    echo "autowidth: true
  }) ; 
});";
		echo '</script>';
	}
	
	/**
	 * Returns the Javascript part that is needed to write the column model into jqGrid.
	 * The returned value could look like this:
	 * 
	 * colNames:['Login','First name', 'Last name','Publisher'],
	 * colModel :[ 
	 *       {name:'login', index:'login', width:55}, 
	 *       {name:'first_name', index:'first_name', width:90}, 
	 *       {name:'last_name', index:'last_name', width:80}, 
	 *       {name:'publisher_name', index:'label', width:80}
	 *     ],
	 *
	 * @return string
	 */
	public function getColumnsDefinition() {
		$isEditionMode = BaseWidgetUtils::isWidgetEditionEnabled();
		
		$str = "colNames:[";
		$columnsTitles = array();
		$columnsDesc = array();
		foreach ($this->columns as $column) {
			if (!$isEditionMode) {
				$columnsTitles[] = '"'.addslashes($column->getTitle()).'"';
			} else {
				// Let's try to find the object in Mouf.
				$manager = MoufManager::getMoufManager();
				$instanceName = $manager->findInstanceName($column);
				if ($instanceName == false) {
					$columnsTitles[] = '"'.htmlentities($column->getTitle()).'"';
				} else {
					$columnsTitles[] = '"'.htmlentities($column->getTitle()).addslashes(" <a onclick='window.location=\"".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."\"; return false;'>edit</a>").'"';
				}
			}
			$formatter = $column->getFormatter();
			$formatStr = "";
			if ($formatter != null) {
				if ($formatter instanceof LinkFormatter) {
					$formatStr = ", formatter:'showlink', formatoptions:{baseLinkUrl:'".ROOT_URL.plainstring_to_htmloutput($formatter->baseLinkUrl)."'";
					if ($formatter->addParam != null) {
						$formatStr .= ", addParam: '".plainstring_to_htmloutput($formatter->addParam)."'";
					}
					if ($formatter->idName != null) {
						$formatStr .= ", idName: '".plainstring_to_htmloutput($formatter->idName)."'";
					}
					$formatStr .= "}";
				} elseif ($formatter instanceof CheckboxFormatter) {
					$formatStr = ", formatter:'checkbox', formatoptions:{disabled:".($formatter->disabled?"true":"false")."}";
				} elseif ($formatter instanceof DateFormatter) {
					$srcFormat = $formatter->sourceFormat;
					if ($srcFormat == "timestamp") {
						// jqGrid does not support timestamps. We will convert those on the server side.
						$srcFormat = "Y-m-d H:i:s";
					}
					$formatStr = ", formatter:'date', formatoptions:{srcformat:'".addslashes($formatter->sourceFormat)."', destformat:'".addslashes($formatter->getDestFormat())."'}";
				} elseif ($formatter instanceof CurrencyFormatter) {
					$formatStr = ", formatter:'currency', formatoptions:{thousandsSeparator:'".addslashes($formatter->thousandsSeparator)."',
											decimalSeparator:'".addslashes($formatter->decimalSeparator)."',
											decimalPlaces:".$formatter->decimalPlaces.",
											prefix:'".addslashes($formatter->prefix)."',
											suffix:'".addslashes($formatter->suffix)."',
											defaultValue:'".addslashes($formatter->defaultValue)."'}";
				} elseif ($formatter instanceof NumberFormatter) {
					$formatStr = ", formatter:'number', formatoptions:{thousandsSeparator:'".addslashes($formatter->thousandsSeparator)."',
											decimalSeparator:'".addslashes($formatter->decimalSeparator)."',
											decimalPlaces:".$formatter->decimalPlaces.",
											defaultValue:'".addslashes($formatter->defaultValue)."'}";
				} elseif ($formatter instanceof CustomFunctionFormatter) {
					// Do nothing
				} else {
					throw new Exception("Unsupported formatter for jqGrid: ".get_class($formatter));
				}
				
			}
			$columnsDesc[] = '{name:"'.htmlentities($column->getSortColumn()).'", index:"'.htmlentities($column->getSortColumn()).'", width:"'.htmlentities($column->getWidth()).'"'.$formatStr.'}';
			
		}
		$str .= implode(", ", $columnsTitles);
		
		$str .= "], \n";
		$str .= "colModel :[ \n";
		$str .= implode(", ", $columnsDesc);
		$str .= "], \n";
		return $str;
	}
	
	/**
	 * This functions generates the XML answer to a query performed by jqGrid.
	 *
	 * @param int $page
	 * @param int $rows
	 * @param int $sidx
	 * @param int $sord
	 */
	public function printXmlData($page, $rows, $sidx, $sord) {
		// First, check rights.
		if ($this->displayCondition != null && $this->displayCondition->isOk($this) == false) {
			return;
		}
		
		// Preliminary checks:
		if ($this->idColumn == null) {
			throw new Exception('Error while displaying a datagrid: the property "idColumn" must be set.');
		}
		
		$this->datasource->setOrderColumn($sidx);
		$this->datasource->setOrder($sord);
		
		// to the url parameter are added 4 parameters as described in colModel
		// we should get these parameters to construct the needed query
		// Since we specify in the options of the grid that we will use a GET method 
		// we should use the appropriate command to obtain the parameters. 
		// In our case this is $_GET. If we specify that we want to use post 
		// we should use $_POST. Maybe the better way is to use $_REQUEST, which
		// contain both the GET and POST variables. For more information refer to php documentation.
		// $page: Get the requested page. By default grid sets this to 1. 
		// $rows: get how many rows we want to have into the grid - rowNum parameter in the grid 
		// $sidx: get index row - i.e. user click to sort. At first time sortname parameter -
		// 		after that the index from colModel 
		// $sord: sorting order - at first time sortorder  
 
		// if we not pass at first time index use the first column for the index or what you want
		if(!$sidx) $sidx =1; 
		
		if ($this->datasource instanceof XajaUpdatableDataSourceInterface) {
			$count = $this->datasource->getGlobalCount($this->dsParams);
		} else {
			$count = count($this->datasource);
		}
		// calculate the total pages for the query 
		if( $count > 0 ) { 
			$total_pages = ceil($count/$rows); 
		} else { 
			$total_pages = 0; 
		} 
		
		// if for some reasons the requested page is greater than the total 
		// set the requested page to total page 
		if ($page > $total_pages) $page=$total_pages;
		 
		// calculate the starting position of the rows 
		$start = $rows*$page - $rows;
		
		// if for some reasons start position is negative set it to 0 
		// typical case is that the user type 0 for the requested page 
		if($start <0) $start = 0; 
		
		if ($this->datasource instanceof XajaUpdatableDataSourceInterface) {
			$this->datasource->load($this->dsParams, $start, $rows);
		}
		
		
		
		// we should set the appropriate header information. Do not forget this.
		header("Content-type: text/xml;charset=utf-8");
		 
		$s = "<?xml version='1.0' encoding='utf-8'?>";
		$s .=  "<rows>";
		$s .= "<page>".$page."</page>";
		$s .= "<total>".$total_pages."</total>";
		$s .= "<records>".$count."</records>";
		
		// be sure to put text data in CDATA
		//foreach ($this->datasource as $row) {
		for ($i=$start; $i<min($start+$rows, $count); $i++) {
			if (!isset($this->datasource[$i])) {
				throw new Exception("Unable to find the record number ".$i." in the datasource. It should exist according to the number of records returned by the datasource.
				This suggest there might be a problem in the datasource.");
			}
			$row = $this->datasource[$i];
			$id = $this->idColumn->getValue($row);			
		    $s .= "<row id='". htmlentities($id)."'>";
		    foreach ($this->columns as $column) {
		    	if ($column->getFormatter() instanceof DateFormatter && $column->getFormatter()->sourceFormat == "timestamp") {
		    		$s .= "<cell>". date("Y-m-d H:i:s",$column->getValue($row))."</cell>";
		    	} else if ($column->getFormatter() instanceof CustomFunctionFormatter) {
		    		$className = $column->getFormatter()->className;
		    		$functionName = $column->getFormatter()->functionName;
		    		if (!empty($className)) {
		    			$result = call_user_func(array($className, $functionName), $row);
		    		} else {
		    			$result = call_user_func($functionName, $row);
		    		}
		    		$s .= "<cell>". htmlentities($result)."</cell>";
		    	} else {
		    		$s .= "<cell>". htmlentities($column->getValue($row))."</cell>";
		    	}
		    }
		    $s .= "</row>";
		}
		$s .= "</rows>"; 
		 
		echo $s;
	}
}
?>