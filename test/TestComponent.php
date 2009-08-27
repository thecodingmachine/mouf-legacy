<?php

/**
 * Test class.
 * 
 * @Component
 */
class TestComponent {
	
	/**
	 * @Property
	 * @var array<string>
	 */
	public $arrayOfStrings;
	
	/**
	 * @Property
	 * @var array<LogInterface>
	 */
	public $arrayOfComponents;
	
	/**
	 * @Property
	 * @var array<string, string>
	 */
	public $associativeArrayOfStrings;

	/**
	 * @Property
	 * @var array<string, LogInterface>
	 */
	public $associativeArrayOfComponents;
	
}
?>