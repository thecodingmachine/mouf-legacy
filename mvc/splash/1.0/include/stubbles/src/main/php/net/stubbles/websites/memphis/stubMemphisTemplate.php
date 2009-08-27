<?php
/**
 * Interface for template implementations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
/**
 * Interface for template implementations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
interface stubMemphisTemplate extends stubObject
{
    /**
     * registry key for template dir setting
     */
    const REGISTRY_KEY_DIR = 'net.stubbles.websites.memphis.templateDir';

    /**
     * constructor
     *
     * @param  string  $baseDir     directory where template files can be found
     * @param  array   $namespaces  optional  namespaces for patTemplate tags, default patTemplate
     * @param  array   $options     optional  configuration options for patTemplate
     */
    #public function __construct($baseDir, array $namespaces = array(), array $options = array());

    /**
     * enables the cache
     *
     * If no cacheDir is given cache files will be stored in
     * stubConfig::getCachePath() . '/patTemplate'. The default prefix will be
     * 'tmpl_', the default patTemplate cache driver used will be the File driver.
     * 
     * @param  string  $cacheDir  optional  directory to put cache files into
     * @param  string  $prefix    optional  prefix for cache files
     * @param  string  $type      optional  patTemplate cache driver
     */
    public function enableCache($cacheDir = null, $prefix = 'tmpl_', $type = 'File');

    /**
     * open any input and parse for patTemplate tags
     *
     * @param   string  $input      name of the input (filename, shm segment, etc.)
     * @param   string  $reader     optional  driver that is used as reader, you may also pass a Reader object
     * @param   array   $options    optional  additional options that will only be used for this template
     * @param   string  $parseInto  optional  name of the template that should be used as a container
     * @return  bool    true, if the template could be parsed, false otherwise
     * @throws  stubException
     */
    public function readTemplatesFromInput($input, $reader = 'File', array $options = null, $parseInto = null);

    /**
     * add a variable to a template
     *
     * A variable may also be an indexed array, but not an associative array!
     *
     * @param   string  $template  name of the template
     * @param   string  $varname   name of the variable
     * @param   mixed   $value     value of the variable
     * @return  bool
     */
    public function addVar($template, $varname, $value);
    /**
     * adds several variables to a template
     *
     * Each Template can have an unlimited amount of its own variables
     * $variables has to be an assotiative array containing variable/value pairs.
     *
     * @param   string  $template   name of the template
     * @param   array   $variables  assotiative array of the variables
     * @param   string  $prefix     optional  prefix for all variable names
     * @return  bool
     */
    public function addVars($template, $variables, $prefix = '');

    /**
     * adds a global variable
     *
     * Global variables are valid in all templates of this object.
     * A global variable has to be scalar, it will be converted to a string.
     *
     * @param   string  $varname  name of the global variable
     * @param   string  $value    value of the variable
     * @return  bool
     */
    public function addGlobalVar($varname, $value);

    /**
     * Adds several global variables
     *
     * Global variables are valid in all templates of this object.
     *
     * $variables is an associative array, containing name/value pairs of the variables.
     *
     * @param   array   $variables  array containing the variables
     * @param   string  $prefix     optional  prefix for variable names
     * @return  bool
     */
    public function addGlobalVars($variables, $prefix = '');

    /**
     * returns a parsed template
     *
     * If the template already has been parsed, it just returns the parsed template.
     * If the template has not been loaded, it will be loaded.
     *
     * @param   string  $name          optional  name of the template
     * @param   bool    $applyFilters  optional  whether to apply output filters
     * @return  string
     * @throws  stubException
     */
    public function getParsedTemplate($name = null, $applyFilters = false);
}
?>