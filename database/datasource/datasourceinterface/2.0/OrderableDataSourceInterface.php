<?php
/**
 * The interface implemented by datasource that can be ordered according to their elements.
 *
 */
interface OrderableDataSourceInterface {
	
	/**
	 * Sets the order column that will be used for this datasource.
	 *
	 * @Property
	 * @param string $order_column
	 */
	public function setOrderColumn($order_column);
	
	/**
	 * Sets the order that will be used for this datasource (can be ASC or DESC).
	 *
	 * @Property
	 * @OneOf("ASC","DESC")
	 * @param string $order
	 */
	public function setOrder($order);
}
?>