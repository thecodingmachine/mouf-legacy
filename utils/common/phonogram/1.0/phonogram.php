<?php
/**
 * A simple package that provides functions to hash a string into phonetic representation. 
 * By using this package, the coder can easily make a proximity compare.
 * @Component
 * @author qing
 * @version 1.0
 */
class Phonogram {
	/**
	 * List of words suppressed before performing the hash. 
	 * @var array<string>
	 * @Property
	 */
	public $suppressedWords=array("s "," de "," d'"," du "," le "," la "," les "," un "," une ");
	
	/**
	 * List of consones to be replaced. 
	 * @var array<string,string>
	 * @Property
	 */
	public $replacedConsones=array("b"=>"p", "c"=>"s", "d"=>"t", "g"=>"j", "h"=>"","q"=>"k","ç"=>"s");

	/**
	 * List of consones (only those characteres will be kept). 
	 * @var array<string>
	 * @Property
	 */
	public $consones=array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n","p", "q", "r", "s","t", "v", "w", "x", "z");
	
	
	public function transform($mot) {
		$mot=' '.$mot.' ';
		$mot = strtolower($mot);
		$mot = str_replace($this->suppressedWords," ",$mot);
		$mot = strtr($mot,$this->replacedConsones);
		$words= preg_split('//', $mot, -1,PREG_SPLIT_NO_EMPTY);
		foreach($words as $word){
			if (array_search($word, $this->consones)){ 
				$newword.=$word;
			}
		}
		return $newword;
	}
}

?>