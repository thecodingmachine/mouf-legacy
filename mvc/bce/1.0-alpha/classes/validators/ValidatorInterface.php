<?php
interface ValidatorInterface{
	
	
	public function validate($value);
	
	public function loadRules();
	
	public function getJsRules();
	
}