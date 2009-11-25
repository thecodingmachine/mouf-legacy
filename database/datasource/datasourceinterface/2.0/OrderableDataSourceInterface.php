<?php
/**
 * The interface implemented by datasource that can be ordered according to their elements.
 *
 */
interface OrderableDataSourceInterface extends DataSourceInterface {
	
	/**
	 * Returns all Data Source' order columns array
	 * @return array<DataSourceColumnInterface>
	 */
	public function getOrderColumns();
	
	/**
	 * Returns all Data Source' orders array
	 * @return array<string>
	 */
	public function getOrders();
	
		/**
	 * Sets the orders array. Previous array is overwritten.
	 *
	 * @Property
	 * @param array<DataSourceColumnInterface> $columns
	 */
	public function setOrderColumns(array $columns=array());
	
	/**
	 * Sets the order columns array. Previous array is overwritten.
	 *
	 * @Property
	 * @param array<string> $orders
	 */
	public function setOrders($orders=array());
}
?>