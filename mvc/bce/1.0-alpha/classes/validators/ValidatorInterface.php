<?php
interface ValidatorInterface{
	
	
	public function validate($value);
	
	public function getHtmlAttribute();
	
	public function getJsRules();
	
}