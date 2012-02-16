<?php 
/**
 * This class is in charge of including and keeping track of Javascript and CSS libraries into an HTML page.
 * <p>JS and CSS files are grouped into <b>WebLibraries</p>. If you want to add a new library, just create an instance
 * of the <b>WebLibrary</b> class, and add it to the <b>WebLibraryManager</b>.</p>
 * <p>You can use the <b>WebLibraryManager</b> class to add JS/CSS libraries. It will keep track of dependencies and ensure each file is included
 * only once.</p>

 * <p>If you have specific needs and don't want to use the <b>WebLibrary</b> class, you can either create your own class
 * that implements the WebLibraryInterface, or provide your own "renderer".</p>
 * 
 * 
 * @author David Négrier
 * @Component
 */
class WebLibraryManager implements HtmlElementInterface {
	
	/**
	 * The array of all included libraries.
	 * 
	 * @var array<WebLibraryInterface>
	 */
	private $webLibraries = array();
	
	/**
	 * false if the toHtml method has not yet been called, true if it has been called.
	 * @var boolean
	 */
	private $rendered = false;
	
	/**
	 * Adds a library to the list of libraries that should be loaded in the web page.
	 * <p>The function will also load the dependencies (if any) and will have no effect if the library has already been loaded.</p>
	 * 
	 * @param WebLibraryInterface $library
	 */
	public function addLibrary(WebLibraryInterface $library) {
		if ($this->rendered) {
			throw new WebLibraryException("The libraries have already been rendered. This call to addLibrary should be performed BEFORE the toHtml method of WebLibraryManager is called.");
		}
		if (!array_search($library, $this->webLibraries) === false) {
			// Let's start by adding dependencies.
			$dependencies = $library->getDependencies();
			if ($dependencies) {
				foreach ($dependencies as $dependency) {
					/* @var $dependency WebLibraryInterface */
					$this->addLibrary($dependency);
				}
			}
			
			$this->webLibraries[] = $library;
		}
	}
	
	// TODO: add: addJs and addCss file
	
	/**
	 * The list of all libraries that should be loaded in the web page.
	 * <p>If you do not pass all dependencies of a library, the dependencies will be loaded automatically.</p>
	 * 
	 * @Property
	 * @param array<WebLibraryInterface> $libraries
	 */
	public function setWebLibraries($libraries) {
		foreach ($libraries as $library) {
			$this->addLibrary($library);
		}
	}
	
	/**
	 * Renders the HTML in charge of loading CSS and JS files.
	 * The Html is echoed directly into the output.
	 * This function should be called within the head tag.
	 *
	 */
	public function toHtml() {
		if ($this->rendered) {
			throw new WebLibraryException("The library has already been rendered.");
		}
		
		$defaultWebLibraryRenderer = null;
		
		foreach ($this->webLibraries as $library) {
			/* @var $library WebLibraryInterface */
			$renderer = $library->getRenderer();
			if ($renderer == null) {
				if ($defaultWebLibraryRenderer == null) {
					$defaultWebLibraryRenderer = MoufManager::getMoufManager()->getInstance('defaultWebLibraryRenderer');
				}
				$renderer = $defaultWebLibraryRenderer;
			}
			/* @var $renderer WebLibraryRendererInterface */
			$renderer->toCssHtml($library);
		}

		foreach ($this->webLibraries as $library) {
			/* @var $library WebLibraryInterface */
			$renderer = $library->getRenderer();
			if ($renderer == null) {
				$renderer = $defaultWebLibraryRenderer;
			}
			/* @var $renderer WebLibraryRendererInterface */
			$renderer->toJsHtml($library);
		}
		
		foreach ($this->webLibraries as $library) {
			/* @var $library WebLibraryInterface */
			$renderer = $library->getRenderer();
			if ($renderer == null) {
				$renderer = $defaultWebLibraryRenderer;
			}
			/* @var $renderer WebLibraryRendererInterface */
			$renderer->toAdditionalHtml($library);
		}
		
		$this->rendered = true;
	}
}
?>