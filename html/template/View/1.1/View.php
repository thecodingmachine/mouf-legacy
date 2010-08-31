<?php

namespace html\template\view;

/**
 * A View represents an HTML page. It is based on a template, and contains the content to be filled in a template.
 * You should have one view per HTML page in your application, while you should have only one template, used by all the views.
 * 
 * @Component
 */
class View extends \BaseTemplate {
	
	/**
	 * The template to use for this view.
	 * Note: you can also chain views.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;

	/**
	 * Draws the view by calling the template and filling the template with all appropriate content.
	 */
	public function draw(){
	
		foreach ($this->left as $elem) {
			$this->template->addLeftHtmlElement($elem);
		}
		foreach ($this->content as $elem) {
			$this->template->addContentHtmlElement($elem);
		}
		foreach ($this->right as $elem) {
			$this->template->addRightHtmlElement($elem);
		}
		foreach ($this->header as $elem) {
			$this->template->addHeaderHtmlElement($elem);
		}
		foreach ($this->footer as $elem) {
			$this->template->addFooterHtmlElement($elem);
		}
		foreach ($this->head as $elem) {
			$this->template->addHeadHtmlElement($elem);
		}
		if ($this->title) {
			$this->template->setTitle($this->title);
		}
		if (is_array($this->css_files)) {
			foreach ($this->css_files as $elem) {
				$this->template->addCssFile($elem);
			}
		}
		
		$this->template->draw();
	}
	
}
?>