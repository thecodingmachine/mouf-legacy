<?php

/**
 * A column embedding an HTML link in it.
 *
 * @Component
 */
class DataGridLinkColumn extends DataGridColumn {
	
	/**
	 * The base link URL that the link will lead to.
	 * This is relative to the root of the web application, and it should not start with a /
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $baseLinkUrl;
	
	/**
	 * If passed, this string is added after the link.
	 * For instance, if you pass "&amp;action=edit", your link will be:
	 *  http://server/baseLinkUrl?id=[id]&amp;action=edit
	 * 
	 * @Property
	 * @var string
	 */
	public $addParam;

	private $dataSourceLinkColumn;
	
	/**
	 * The column to be used in the datasource as a source for the web link.
	 *
	 * @Property
	 * @Compulsory
	 * @param DataSourceColumnInterface $dataSourceColumn
	 */
	public function setDataSourceLinkColumn(DataSourceColumnInterface $dataSourceLinkColumn) {
		$this->dataSourceLinkColumn = $dataSourceLinkColumn;
	}
	
	public function __construct($baseLinkUrl=null, $addParam=null, $idName=null) {
		$this->baseLinkUrl = $baseLinkUrl;
		$this->addParam = $addParam;
	}
	
	/**
	 * Returns the value associated to the row passed in parameter for this column.
	 *
	 * @param array<object> $row
	 * @param int $rowid
	 * @return string
	 */
	public function getValue($row, $rowid) {
		$value = parent::getValue($row, $rowid);
		
		if ($this->dataSourceLinkColumn == null) {
			error_log("Error: DataGridLinkColumn has no DataSourceColumn associated for the link source.");
			throw new Exception("Error: DataGridLinkColumn has no DataSourceColumn associated for the link source.");
		}
		$name = $this->dataSourceLinkColumn->getName();
		$linkValue = $row->$name;
		
		$value = "<a href='".ROOT_URL.$this->baseLinkUrl.$linkValue.$this->addParam."'>".$value."</a>";
		return $value;
	}
}
?>