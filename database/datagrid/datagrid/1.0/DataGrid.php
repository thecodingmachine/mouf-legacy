<?php
/**
 * This class represents a data grid, that is data formatted in a way that can be easily represented in an HTML grid.
 * This class is not very useful by itself. You might want to use a class extending this class, that provides a real
 * behaviour for displaying grids. 
 *
 */
class DataGrid {
		
	/**
	 * @var DataColumnInterface
	 */
	protected $idColumn;
	protected $columns = array();
	
	/**
	 * @var XajaDataSourceInterface
	 */
	protected $datasource;
	
	/**
	 * Params to be passed to the datasource.
	 *
	 * @var array<string,string>
	 */
	protected $dsParams;
	
	protected $restrictedRight;
	protected $restrictedRightScope;
	
	/**
	 * Constructor for DataGrid
	 *
	 * @param XajaDataSourceInterface $datasource The datasource that will be used by the datagrid.
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
	 * Sets the ID column. This is a special column that contains the ID for the row (the primary key)
	 *
	 * @Property
	 * @Compulsory 
	 * @param DataColumnInterface $column
	 */
	public function setIdColumn(DataColumnInterface $column) {
		$this->idColumn = $column;
	}
	
	/**
	 * Sets the array of columns.
	 *
	 * @Property
	 * @Compulsory 
	 * @param array<DataColumnInterface> $columns
	 */
	public function setColumns(array $columns) {
		$this->columns = $columns;
	}
	
	/**
	 * Adds a column to the grid.
	 *
	 * @param DataColumnInterface $column
	 */
	public function addColumn(DataColumnInterface $column) {
		$this->columns[] = $column;
	}
	
	/**
	 * The datasource to use by the grid.
	 *
	 * @Property
	 * @Compulsory 
	 * @param XajaDataSourceInterface $ds
	 */
	public function setDataSource(XajaDataSourceInterface $ds) {
		$this->datasource = $ds;
	}
	
	/**
	 * The datagrid can be restricted to some persons only.
	 * In this case, the right required to view the data grid should be passed in this property.
	 *
	 * @Property
	 * @param string $right
	 */
	public function setRestrictedRight($right) {
		$this->restrictedRight = $right;
	}
	
	/**
	 * The datagrid can be restricted to some persons only.
	 * This is the scope of the right used to restrict the datagrid.
	 * The scope is optional.
	 *
	 * @Property 
	 * @param string $rightScope
	 */
	public function setRestrictedRightScope($rightScope) {
		$this->restrictedRightScope = $rightScope;
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