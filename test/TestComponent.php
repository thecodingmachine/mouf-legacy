<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 

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