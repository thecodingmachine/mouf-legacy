<?php

require_once 'MoufPackageDescriptor.php';
require_once 'MoufDependencyDescriptor.php';

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
	 * An Url to access the logo, relative to the package root element.
	 *
	 * @var string
	 */
	private $logoPath;
	
	/**
	 * The revision number for this package.
	 * A revision number is an integer that identifies a version of the package.
	 * Unlike the version number, all packages with the same revision number should provide the same set of features.
	 * Basically, if you fix a bug in a package, rather than making a new version, you can make a new revision (by
	 * increasing the revision number).
	 * The Mouf UI provides ways to auto-update packages to the latest revision number, while updates to a new
	 * version must be done manually.
	 * 
	 * @var int
	 */
	private $revision;

	/**
	 * Where the package currently is.
	 * If "null", this is local, if set to a string, this is the repository the package is in.
	 * 
	 * Please not this is NOT the place the package was downloaded from.
	 * 
	 * @var MoufRepository
	 */
	private $currentLocation;
	
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
	 * The list of files that contains external declarations of components, relative to the package directory.
	 *
	 * @var array<string>
	 */
	private $externalComponentRequires;

	/**
	 * The list of files to be required by Mouf in its UI, relative to the package directory.
	 * This can be useful to extend the template and provide additional features.
	 *
	 * @var array<string>
	 */
	private $adminRequires;
	
	public function __construct() {
		
	}
	
	/**
	 * Initializes the MoufPackage variables with the package.xml file passed in parameter.
	 * 
	 * @param string $fileName The file path to the package.xml file.
	 * @throws MoufException
	 */
	public function initFromFile($fileName) {
		$this->packageFileName = $fileName;
		$this->packageDir = dirname($fileName);
		
		if (!file_exists($fileName)) {
			throw new MoufException("Unable to load file ".$fileName);
		}
		$this->packageDescriptor = MoufPackageDescriptor::getPackageDescriptorFromPackageFile($this->packageFileName);
		
		$this->packageSimpleXml = simplexml_load_file($fileName);
		
		$this->initFromXml();
	}
	
	
	/**
	 * Initializes the MoufPackage variables with the xml string file passed in parameter.
	 * 
	 * @param string $xmlStr The content of the package.xml file, as a string.
	 * @throws MoufException
	 */
	/*public function initFromXmlString($xmlStr) {
		$this->packageSimpleXml = simplexml_load_string($xmlStr);
		$this->initFromXml();
	}*/

	/**
	 * Initializes the MoufPackage variables once the $this->packageSimpleXml
	 * variable was initialized. 
	 * 
	 */
	private function initFromXml() {
		$this->displayName = (string)$this->packageSimpleXml->displayName;
		$this->shortDescription = (string)$this->packageSimpleXml->shortDescription;
		$this->docUrl = (string)$this->packageSimpleXml->docUrl;
		$this->logoPath = (string)$this->packageSimpleXml->logo;
		$this->revision = (string)$this->packageSimpleXml->revision;
		
		if ($this->revision == null) {
			$this->revision = 0;
		}
		if (!is_numeric($this->revision)) {
			throw new MoufException("The revision number for package {$this->displayName} must be an integer.");
		}
		
		
		$depList = array();
		$dependencies = $this->packageSimpleXml->dependencies;
		if ($dependencies) {
			foreach ($dependencies->dependency as $dependencyXml) {
				$revision = (int) $dependencyXml->revision or 0;
				$depList[] = new MoufDependencyDescriptor((string)$dependencyXml->group,(string)$dependencyXml->name,(string)$dependencyXml->version, $revision, (string)$dependencyXml->repository);
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
		
		$externalList = array();
		$requires = $this->packageSimpleXml->externalComponentRequires;
		if ($requires) {
			foreach ($requires->require as $require) {
				$externalList[] = (string)$require;
			}
		}
		$this->externalComponentRequires = $externalList;
		
		
		$adminRequiresList = array();
		$adminRequires = $this->packageSimpleXml->adminRequires;
		if ($adminRequires) {
			foreach ($adminRequires->require as $require) {
				$adminRequiresList[] = (string)$require;
			}
		}
		$this->adminRequires = $adminRequiresList;	
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
	 * Returns the short description
	 *
	 * @return string
	 */
	public function getShortDescription() {
		return $this->shortDescription;
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
	 * Returns the path to the logo, relative to the root URL.
	 *
	 * @return string
	 */
	public function getLogoPath() {
		return $this->logoPath;
	}
	
	/**
	 * Returns the revision number for the package.
	 * 
	 * @return int
	 */
	public function getRevision() {
		return $this->revision;
	}
	
	/**
	 * Returns the package directory, relatives to the plugin directory.
	 *
	 * @return string
	 */
	public function getPackageDirectory() {
		return $this->packageDir;
	}
	
	/**
	 * Returns an array of MoufDependencyDescriptor.
	 *
	 * @return array<MoufDependencyDescriptor>
	 */
	public function getDependenciesAsDescriptors() {
		return $this->dependencies;
	}
	
	/**
	 * Returns the list of required files, relative to the root of the package.
	 *
	 * @array<string>
	 */
	public function getRequiredFiles() {
		return $this->requires;
	}
	
	/**
	 * Returns the list of required files that contain declaration of external components, relative to the root of the package.
	 *
	 * @array<string>
	 */
	public function getExternalComponentsRequiredFiles() {
		return $this->externalComponentRequires;
	}

	/**
	 * Returns the list of required files for the admin, relative to the root of the package.
	 *
	 * @array<string>
	 */
	public function getAdminRequiredFiles() {
		return $this->adminRequires;
	}

	/**
	 * Returns where the package currently is.
	 * If "null", this is local, if set to a string, this is the repository the package is in.
	 * 
	 * Please not this is NOT the place the package was downloaded from.
	 * 
	 * @return MoufRepository
	 */
	public function getCurrentLocation() {
		return $this->currentLocation;
	}
	
	/**
	 * Returns a PHP array that describes the package.
	 * The array does not contain all available information, only enough information to display the list of packages in the Mouf interface.
	 * 
	 * The structure of the array is:
	 * 	array("displayName" => string, "shortDescription"=> string, "docUrl"=>string, "logoPath"=>string, "dependencies"=>array<dependencyDescriptorArray>)
	 * 
	 * return array<string, MoufPackage>
	 */
	public function getJsonArray() {
		$array = array("displayName"=>$this->displayName,
			"shortDescription"=>$this->shortDescription,
			"docUrl"=>$this->docUrl,
			"logoPath"=>$this->logoPath,
			"revision"=>$this->revision);

		if (!empty($this->dependencies)) {
			$array['dependencies'] = array();
			foreach ($this->dependencies as $dependency) {
				/* @var $dependency MoufPackageDescriptor */
				$array['dependencies'][] = $dependency->getJsonArray();
			}
		}
		
		return $array;		
	}

	/**
	 * Returns a MoufPackage from a PHP array describing the package.
	 * Note: since the PHP array does not contain all the available information, the object will be incomplete.
	 * However, it has enough information to display the list of packages available for download.
	 * 
	 * The structure of the array is:
	 * 	array("displayName" => string, "shortDescription"=> string, "docUrl"=>string, "logoPath"=>string, "dependencies"=>array<dependencyDescriptorArray>)
	 * 
	 * @param array $array
	 * @return MoufPackage
	 */
	public static function fromJsonArray($array, $groupName, $packageName, $version, MoufRepository $repository) {
		$moufPackage = new MoufPackage();
		$moufPackage->displayName = $array['displayName'];
		$moufPackage->shortDescription = $array['shortDescription'];
		$moufPackage->docUrl = $array['docUrl'];
		$moufPackage->logoPath = $array['logoPath'];
		$moufPackage->revision = $array['revision'];
		$moufPackage->packageDir = ".".$groupName."/".$packageName."/".$version;
		$moufPackage->currentLocation = $repository;
		
		$moufPackage->packageDescriptor = new MoufPackageDescriptor($groupName, $packageName, $version);
		
		if (!empty($array['dependencies'])) {
			foreach ($array['dependencies'] as $dependency) {
				$moufPackage->dependencies[] = MoufDependencyDescriptor::fromJsonArray($dependency);
			}
		}
		return $moufPackage;
	}
}
?>