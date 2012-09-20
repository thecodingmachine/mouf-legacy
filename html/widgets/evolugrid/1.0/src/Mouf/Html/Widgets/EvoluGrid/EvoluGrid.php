<?php
namespace Mouf\Html\Widgets\EvoluGrid;

/**
 * This class represents a grid that can be rendered using the EvoluGrid JS jQuery plugin.
 *
 * @author David Negrier
 * @Component
 */
class EvoluGrid implements \HtmlElementInterface {
	
	/**
	 * @var string
	 */
	private $id;
	
	/**
	 * @var string
	 */
	private $class;
	
	/**
	 * @var boolean
	 */
	private $exportCSV;
	
	/**
	 * @var int
	 */
	private $limit;
	
	/**
	 * @var string
	 */
	private $url;
	
	/**
	 * @var \DynamicDataSource
	 */
	private $datasource;

	/**
	 * @var array<EvoluColumn>
	 */
	private $columns = array();

	/**
	 * The data to be displayed by the grid.
	 *
	 * @var array<stdObject>
	 */
	private $rows = array();

	private $count = null;
	
	/**
	 * The id of the evolugrid.
	 * 
	 * @Property
	 * @param string $id
	 */
	public function setId($id) {
		if($id != '')
			$this->id = $id;
		else
			$this->id = 'evolugrid__id__'.rand(100000,999999);
	}
	
	/**
	 * The class of the evolugrid.
	 * 
	 * @Property
	 * @param string $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}
	
	/**
	 * Export the grid to CSV format.
	 * 
	 * @Property
	 * @param boolean $exportCSV
	 */
	public function setExportCSV($exportCSV) {
		$this->exportCSV = $exportCSV;
	}
	
	/**
	 * Limit of the grid before pagination.
	 * 
	 * @Property
	 * @param int $limit
	 */
	public function setLimit($limit) {
		if($limit)
			$this->limit = $limit;
		else
			$this->limit = 100;
	}
	
	/**
	 * URL of the controller to load data.
	 * 
	 * @Property
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = ROOT_URL.$url;
	}

	/**
	 * Add a new column to the grid.
	 *
	 * @param EvoluColumn $column
	 */
	public function addColumn(EvoluColumn $column) {
		$this->columns[] = $column;
	}

	public function setRows($rows) {
		$this->rows = $rows;
	}

	private function getRows() {
		if(!empty($this->rows)) {
			return $this->rows;
		} elseif($this->datasource != null) {
			return array_values($this->datasource->getRows(DS_FETCH_ASSOC));
		} else {
			return array();
		}
	}

	public function addRow($row) {
		$this->rows[] = $row;
	}

	/**
	 * @Property
	 * @param array<EvoluColumn> $columns
	 */
	public function setColumns($columns) {
		$this->columns = $columns;
	}

	public function addColumns($columns) {
		$this->columns[] = $columns;
	}

	/**
	 * The datasource to use by the grid.
	 *
	 * @Property
	 * @Compulsory 
	 * @param \DataSourceInterface $ds
	 */
	public function setDataSource(\DataSourceInterface $ds) {
		$this->datasource = $ds;
	}
	
	/**
	 * The limit and offset of the datasource (to set in the URL callback)
	 * 
	 * @param int $limit
	 * @param int $offset
	 */
	public function setDataSourceLimitOffset($limit, $offset) {
		$this->datasource->setLimit($limit);
		$this->datasource->setOffset($offset);
	}

	/**
	 * Sets the total number of rows (!= from the number of rows returned by the grid, used to paginate)
	 *
	 * @param int $count
	 */
	public function setTotalRowsCount($count) {
		$this->count = $count;
	}

	/**
	 * Outputs the data in the format passed in parameter (json OR csv)
	 * If format is empty, we default to JSON
	 * @var $filePath : a path to create the file, in case we want to send it via mail for instance
	 */
	public function output($format = null, $filename = "data.csv") {
		if ($format == "json" || empty($format)) {

			$jsonMessage = array();

			$descriptor = array();
			$columnsArr = array();
			foreach ($this->columns as $column) {
				/* @var $column EvoluColumn */
				$columnArr = array("title"=>$column->title);
				if ($column->key) {
					$columnArr['display'] = $column->key;
				}
				if ($column->jsrenderer) {
					$columnArr['jsdisplay'] = $column->jsrenderer;
				}
				$columnsArr[] = $columnArr;
			}
			$descriptor['columns'] = $columnsArr;
			if ($this->count !== null) {
				$jsonMessage['count'] = $this->count;
			}
			$jsonMessage['data'] = $this->getRows();

			$jsonMessage['descriptor'] = $descriptor;
			echo json_encode($jsonMessage);
		} elseif ($format == "csv") {

			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: mime/type");
			header("Content-Transfer-Encoding: binary");
			$fp = fopen("php://output", "w");

			$this->outputCsv($fp);
		} else {
			throw new Exception("The output format '".$format."' is not supported");
		}
	}

	public function saveCsv($filePath){
		$fp = fopen($filePath, "w");
		$this->outputCsv($fp);
	}


	private function outputCsv($fp){
		$columnsTitles = array_map(function(EvoluColumn $column) {
			return utf8_decode($column->title);
		}, $this->columns);
		fputcsv($fp, $columnsTitles, ";");
		foreach ($this->getRows() as $row) {
			$columns = array_map(function(EvoluColumn $elem) use ($row) {
				if (is_object($row)) {
					$key = $elem->key;
					if (property_exists($row, $key)) {
						return ($row->$key == "")?" ":utf8_decode($row->$key);
					} else {
						return " ";
					}
				} else {
					if (isset($row[$elem->key])) {
						return ($row[$elem->key] == "")?" ":utf8_decode($row[$elem->key]);
					} else {
						return " ";
					}
				}
			}, $this->columns);
			fputcsv($fp, $columns, ";");

		}

		fclose($fp);
	}


	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	public function toHtml() {
		echo '
			<div id="'.$this->id.'"></div>
			<script type="text/javascript">
				$(document).ready(function() {
				    var descriptor = {
				        url: "'.$this->url.'",
				        tableClasses : "'.$this->class.'",
				        export_csv: '.json_encode($this->exportCSV).',
				        limit: '.$this->limit.'
				    };
				    $("#'.$this->id.'").evolugrid(descriptor);
				});
			</script> 
		';
	}
}