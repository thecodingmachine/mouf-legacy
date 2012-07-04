<?php
/**
 * Defines the mandatory functions that must declare a DAO 
 */
interface DAOInterface{

	/**
	 * Get a bean by it's Id
	 * @param mixed $id
	 * @return mixed the bean object
	 */
	public function getById($id);
	
	/**
	 * Get a new bean record
	 * * @return mixed the new bean object
	 */
	public function create();
	
	/**
	 * 
	 * Peforms saving on a bean object
	 * @param mixed bean object
	 */
	public function save($bean);
	
	/**
	 * Returns the lis of beans
	 * @return array<mixed> array of bean objects
	 */
	public function getList();
	
}