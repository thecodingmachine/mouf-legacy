<?php
/**
 * Basic implementation for a memphis page element.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractPageElement');
/**
 * Basic implementation for a memphis page element.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
abstract class stubMemphisPageElement extends stubAbstractPageElement
{
    /**
     * for which part the page element applies
     *
     * @var  array<string>
     */
    protected $parts = array();

    /**
     * checks whether the page element is available or not
     * 
     * A memphis page element is available if the context contains a member with
     * key part and if the value of this member is inside of the configured parts.
     *
     * @return  bool
     */
    public function isAvailable()
    {
        if (isset($this->context['part']) === false) {
            return false;
        }
        
        if (count($this->parts) === 0) {
            return true;
        }
        
        return in_array($this->context['part'], $this->parts);
    }

    /**
     * set the parts for which the element should be applied
     *
     * @param  string  $parts
     */
    public function setParts($parts)
    {
        $this->parts = array_map('trim', explode(',', $parts));
    }
}
?>