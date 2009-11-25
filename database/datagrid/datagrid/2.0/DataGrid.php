<?php
/**
 * This class represents a data grid, that is data formatted in a way that can be easily represented in an HTML grid.
 * This class is not very useful by itself. You might want to use a class extending this class, that provides a real
 * behaviour for displaying grids. 
 *
 */
class DataGrid {
		
	protected $columns = array();
	
	/**
	 * @var DataSourceInterface
	 */
	protected $datasource;
	
	/**
	 * Params to be passed to the datasource.
	 *
	 * @var array<string,string>
	 */
	protected $dsParams;
	
	/**
	 * This condition must be matched to display the menu.
	 * Otherwise, the menu is not displayed.
	 * The displayCondition is optional. If no condition is set, the menu will always be displayed. 
	 *
	 * @Property
	 * @var ConditionInterface
	 */
	public $displayCondition;
	
	/**
	 * Constructor for DataGrid
	 *
	 * @param DataSourceInterface $datasource The datasource that will be used by the datagrid.
	 * @param array<string,string> $dsParams The list of parameters that will be passed to the datasource.
	 */
	public function __construct($datasource = null, $dsParams = null) {
		$this->datasource = $datasource;
		if ($dsParams != null)
			$this->dsParams = $dsParams;
		else
			$this->dsParams = array();
	}

	/**
	 * The datasource to use by the grid.
	 *
	 * @Property
	 * @Compulsory 
	 * @param DataSourceInterface $ds
	 */
	public function setDataSource(DataSourceInterface $ds) {
		$this->datasource = $ds;
	}

	/**
	 * Returns the datasource used by the datagrid.
	 *
	 * @return DataSourceInterface
	 */
	public function getDataSource() {
		return $this->datasource;
	}
	
	/**
	 * Sets the array of columns.
	 *
	 * @Property
	 * @Compulsory 
	 * @param array<DataGridColumnInterface> $columns
	 */
	public function setColumns(array $columns) {
		$this->columns = $columns;
	}
	
	/**
	 * Adds a column to the grid.
	 *
	 * @param DataGridColumnInterface $column
	 */
	public function addColumn(DataGridColumnInterface $column) {
		$this->columns[] = $column;
	}

	/**
	 * Sets the params to be passed to the datasource.
	 *
	 * @Property
	 * @param array<string, string> $params
	 */
	public function setDatasourceParams(array $params) {
		$this->dsParams = $params;
	}
	
	/**
	 * Adds the params to be passed to the datasource.
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public function addDatasourceParam($key, $value) {
		$this->dsParams[$key] = $value;
	}
}
?>