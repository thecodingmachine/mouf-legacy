<?php
/**
 * The interface implemented by datasource that can be ordered according to their elements.
 *
 */
interface OrderableDataSourceInterface {
	
	/**
	 * Sets an order column that will be used for this datasource.
	 *
	 * @Property
	 * @param string $order_column
	 */
	public function setOrderColumn($order_column);
	
	/**
	 * Sets an order that will be used for a order_column of the datasource (can be ASC or DESC).
	 * 
	 * @Property
	 * @OneOf("ASC","DESC")
	 * @param string $order
	 */
	public function setOrder($order);
	
	/**
	 * Returns the Data Source order, ASC or DESC. 
	 * Note: there can be several orders for the Data Source 
	 * - you can order by name and then by date for example - therefore 
	 * the order is stored in a separated array that is linked to the 
	 * order_column array by Mouf. Links between the two are made within the 
	 * Mouf Interface.
	 * 
	 * @return int
	 */
	public function getOrder($rank);
	
	/**
	 * Returns a Data Source rows' order_column by its rank in the 
	 * order_column array.
	 * Warning: the rank sticks to the input order of the column
	 * you made. For example, if you put 
	 * setOrderColumn("name");
	 * and then 
	 * setOrderColumn("creation_date");
	 * getOrderColumn(1) will return "creation_date".
	 * 
	 * @return int
	 */
	public function getOrderColumn($rank);
	
	/**
	 * Returns all Data Source' order columns array
	 * @return array<string>
	 */
	public function getOrderColumns();
	
	/**
	 * Returns all Data Source' orders array
	 * @return array<string>
	 */
	public function getOrders();
}
?>