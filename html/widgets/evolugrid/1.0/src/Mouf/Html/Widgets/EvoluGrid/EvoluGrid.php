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
	 * @Property
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

	public function addRow($row) {
		$this->rows[] = $row;
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
			$jsonMessage['data'] = $this->rows;

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
		foreach ($this->rows as $row) {
			$columns = array_map(function(EvoluColumn $elem) use ($row) {
				if (isset($row[$elem->key])) {
					return ($row[$elem->key] == "")?" ":utf8_decode($row[$elem->key]);
				} else {
					return " ";
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

	}
}