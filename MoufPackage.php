<?php

require_once 'MoufPackageDescriptor.php';

/**
 * This class represents a package that can be downloaded and used by Mouf.
 *
 */
class MoufPackage {
	
	private $packageFileName;
	
	/**
	 * The simplexml object
	 *
	 * @var SimpleXmlElement
	 */
	private $packageSimpleXml;

	/**
	 * Name of the package, as declared in the package.xml file.
	 *
	 * @var string
	 */
	private $displayName;
	
	/**
	 * Directory of the package, relative to the plugins directory.
	 *
	 * @var string
	 */
	private $packageDir;
	
	/**
	 * A short description for the package
	 *
	 * @var string
	 */
	private $shortDescription;
	
	/**
	 * An Url to access the online doc.
	 *
	 * @var string
	 */
	private $docUrl;
	
	/**
	 * The package descriptor for this package (group, name and version number)
	 *
	 * @var MoufPackageDescriptor
	 */
	private $packageDescriptor;
	
	/**
	 * The list of package dependencies (as package descriptors)
	 *
	 * @var array<MoufPackageDescriptor>
	 */
	private $dependencies;
	
	/**
	 * The list of files to be required, relative to the package directory.
	 *
	 * @var array<string>
	 */
	private $requires;

	/**
	 * The list of files to be required by Mouf in its UI, relative to the package directory.
	 * This can be useful to extend the template and provide additional features.
	 *
	 * @var array<string>
	 */
	private $adminRequires;
	
	public function __construct() {
		
	}
	
	public function initFromFile($fileName) {
		$this->packageFileName = $fileName;
		
		$this->packageSimpleXml = simplexml_load_file($fileName);
		
		$this->displayName = (string)$this->packageSimpleXml->displayName;
		$this->shortDescription = (string)$this->packageSimpleXml->shortDescription;
		$this->docUrl = (string)$this->packageSimpleXml->docUrl;
		
		$this->packageDir = dirname($fileName);
		
		$this->packageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($this->packageFileName);
		
		$depList = array();
		$dependencies = $this->packageSimpleXml->dependencies;
		if ($dependencies) {
			foreach ($dependencies->dependency as $dependencyXml) {
				$depList[] = new MoufPackageDescriptor((string)$dependencyXml->group,(string)$dependencyXml->name,(string)$dependencyXml->version);
			}
		}
		$this->dependencies = $depList;
		
		$requiresList = array();
		$requires = $this->packageSimpleXml->requires;
		if ($requires) {
			foreach ($requires->require as $require) {
				$requiresList[] = (string)$require;
			}
		}
		$this->requires = $requiresList;
		
		$adminRequiresList = array();
		$adminRequires = $this->packageSimpleXml->adminRequires;
		if ($adminRequires) {
			foreach ($adminRequires->require as $require) {
				$adminRequiresList[] = (string)$require;
			}
		}
		$this->adminRequires = $adminRequiresList;
		
		// TODO: develop getters for everything except the packageSimpleXml property.
		
	}
	
	/**
	 * Returns the package descriptor.
	 * The package descriptor contains the name, the group and the version of the package.
	 *
	 * @return MoufPackageDescriptor
	 */
	public function getDescriptor() {
		return $this->packageDescriptor;
	}
	
	/**
	 * Returns the display name
	 *
	 * @return string
	 */
	public function getDisplayName() {
		return $this->displayName;
	}
	
	/**
	 * Returns the documentation url
	 *
	 * @return string
	 */
	public function getDocUrl() {
		return $this->docUrl;
	}
	
	/**
	 * Returns the package directory, relatives to the plugin directory.
	 *
	 * @return string
	 */
	public function getPackageDirectory() {
		return $this->packageDir;
	}
}
?>