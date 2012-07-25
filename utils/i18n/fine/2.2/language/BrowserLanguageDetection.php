<?php 
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


/**
 * Detect the browser language
 * 
 * @author Marc Teyssier
 * @Component
 */
class BrowserLanguageDetection implements LanguageDetectionInterface {
	
	/**
	 * Save the language code
	 * @var string
	 */
	private $language = null;
	
	/**
	 * Returns the language used for the users browser.
	 * The code is stored in a variable to store whether the function is called twice
	 * 
	 * @see plugins/utils/i18n/fine/2.1/language/LanguageDetectionInterface::getLanguage()
	 */
	public function getLanguage() {
		if($this->language)
			return $this->language;
			
		// getting http instruction if not provided
		$str=(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])?$_SERVER['HTTP_ACCEPT_LANGUAGE']:"");
		// exploding accepted languages
		$langs=explode(',',$str);
		// creating output list
		$accepted=array();
		foreach ($langs as $lang) {
			// parsing language preference instructions
			// 2_digit_code[-longer_code][;q=coefficient]
			preg_match('/([a-z]{1,2})(-([a-z0-9]+))?(;q=([0-9\.]+))?/',$lang,$found);
			//ereg('([a-z]{1,2})(-([a-z0-9]+))?(;q=([0-9\.]+))?',$lang,$found);
			// 2 digit lang code
			$code=isset($found[1])?$found[1]:null;
			// lang code complement
			$morecode=isset($found[3])?$found[3]:null;
			// full lang code
			$fullcode=$morecode?$code.'-'.$morecode:$code;
			// coefficient
			$coef=sprintf('%3.1f',isset($found[5])?$found[5]:'1');
			// for sorting by coefficient
			// adding
			$accepted[]=array('code'=>$code,'coef'=>$coef,'morecode'=>$morecode,'fullcode'=>$fullcode);
		}
		// sorting the list by coefficient desc
		krsort($accepted);
		if (isset($accepted[0])) {
			$this->language = $accepted[0]['code'];
		}
		
		return $this->language;
	}
}