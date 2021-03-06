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
	 * The caption for the table.
	 * If none is passed, no caption is displayed.
	 *
	 * @Property
	 * @var string
	 */
	public $caption;
	
	/**
	 * The height of the grid.
	 * Can be set as number (in this case we mean pixels) or as percentage (only 100% is acceped) or value of auto is acceptable.
	 *
	 * @Property
	 * @var string
	 */
	public $height = 150;
	
	/**
	 * Number of rows displayed per page.
	 *
	 * @Property
	 * @var int
	 */
	public $nbRowPerPage = 10;
	
	/**
	 * An array containing the number of rows per page that can be requested (in a drop-down).
	 *
	 * @Property
	 * @var array<int>
	 */
	public $nbRowPerPageList = array(10,20,30);
	
	/**
	 * The page number to display at load time.
	 * If set to null, the first page is displayed.
	 * 
	 * @var int
	 */
	public $pageNo = null;
	
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
		
		$nbRowPerPage = $this->nbRowPerPage;
		if (empty($nbRowPerPage)) {
			$nbRowPerPage = 10;
		}
		
		if (empty($this->nbRowPerPageList)) {
			$strNbRowPerPageList = '10,20,30';
		} else {
			$strNbRowPerPageList = implode(',', $this->nbRowPerPageList);
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
    pager: '#".$pagerId."',";
	if (!empty($this->pageNo)) {
		echo "	page: ".$this->pageNo.",";
    }	
    echo "rowNum:$nbRowPerPage,
    height:$this->height,
    rowList:[$strNbRowPerPageList],";
	if ($this->datasource instanceOf OrderableDataSourceInterface) {
		$orderColumns = $this->datasource->getOrderColumns();
		$orderSorts = $this->datasource->getOrders();
		if (count($orderColumns)>0) {
			if (count($orderColumns) != count($orderSorts)) {
				throw new Exception("In datasource, the orderColumns and orders properties must have the same number of elements.");
			}
    		echo "sortname: '".$orderColumns[0]->getName()."',
    			sortorder: '".$orderSorts[0]."',";
		}
	}
    echo "viewrecords: true,";
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
					$columnsTitles[] = '"'.htmlspecialchars($column->getTitle(), ENT_QUOTES).'"';
				} else {
					$columnsTitles[] = '"'.htmlspecialchars($column->getTitle(), ENT_QUOTES).addslashes(" <a onclick='window.location=\"".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."\"; return false;'>edit</a>").'"';
				}
			}

			$columnsDesc[] = '{name:"'.htmlspecialchars($column->getDataSourceColumn()->getName(), ENT_QUOTES).'", index:"'.htmlspecialchars($column->getDataSourceColumn()->getName(), ENT_QUOTES).'", width:"'.htmlspecialchars($column->getWidth(), ENT_QUOTES).'", align:"'.htmlspecialchars($column->getTextAlign(), ENT_QUOTES).'", resizable:'.(($column->isResizable())?"true":"false").'}';
			
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
	 * @param int $nbRows
	 * @param int $sidx
	 * @param int $sord
	 */
	public function printXmlData($page, $nbRows, $sidx, $sord) {
		// First, check rights.
		if ($this->displayCondition != null && $this->displayCondition->isOk($this) == false) {
			return;
		}
		
		if(!empty($sidx)) {
			$this->datasource->setOrderColumns(array($this->datasource->getColumn($sidx)));
			$this->datasource->setOrders(array($sord));
		}
		
		// to the url parameter are added 4 parameters as described in colModel
		// we should get these parameters to construct the needed query
		// Since we specify in the options of the grid that we will use a GET method 
		// we should use the appropriate command to obtain the parameters. 
		// In our case this is $_GET. If we specify that we want to use post 
		// we should use $_POST. Maybe the better way is to use $_REQUEST, which
		// contain both the GET and POST variables. For more information refer to php documentation.
		// $page: Get the requested page. By default grid sets this to 1. 
		// $nbRows: get how many rows we want to have into the grid - rowNum parameter in the grid 
		// $sidx: get index row - i.e. user click to sort. At first time sortname parameter -
		// 		after that the index from colModel 
		// $sord: sorting order - at first time sortorder  
 
		// if we not pass at first time index use the first column for the index or what you want
		if(!$sidx) $sidx =1; 

		$count = $this->datasource->getRowCount();
		
		// calculate the total pages for the query 
		if( $count > 0 ) { 
			$total_pages = ceil($count/$nbRows); 
		} else { 
			$total_pages = 0; 
		} 
		
		// if for some reasons the requested page is greater than the total 
		// set the requested page to total page 
		if ($page > $total_pages) $page=$total_pages;
		 
		// calculate the starting position of the rows 
		$start = $nbRows*$page - $nbRows;
		
		// if for some reasons start position is negative set it to 0 
		// typical case is that the user type 0 for the requested page 
		if($start <0) $start = 0; 		
		
		$this->datasource->setOffset($start);
		$this->datasource->setLimit($nbRows);
		
		$rows = $this->datasource->getRows(DS_FETCH_OBJ);
		
		
		// we should set the appropriate header information. Do not forget this.
		header("Content-type: text/xml;charset=utf-8");
		 
		$s = "<?xml version='1.0' encoding='utf-8'?>";
		$s .=  "<rows>";
		$s .= "<page>".$page."</page>";
		$s .= "<total>".$total_pages."</total>";
		$s .= "<records>".$count."</records>";
		
		// be sure to put text data in CDATA
		//foreach ($this->datasource as $row) {
		foreach ($rows as $key=>$row) {			
		    $s .= "<row id='". htmlspecialchars($key, ENT_QUOTES)."'>";
		    foreach ($this->columns as $column) {
		    	$value = $column->getValue($row, $key);
		    	$s .= "<cell>".htmlspecialchars($value, ENT_NOQUOTES)."</cell>";		    	
		    }
		    $s .= "</row>";
		}
		$s .= "</rows>"; 
		 
		echo $s;
	}
}
?>