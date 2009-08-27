<?php
/**
 * Filter that requires an argument for its constructor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles_test
 * @subpackage  filterprovider
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter');
/**
 * Filter that requires an argument for its constructor.
 *
 * @package     stubbles_test
 * @subpackage  filterprovider
 */
class FilterWithConstArgs extends stubBaseObject implements stubFilter
{
    /**
     * request value error factory
     *
     * @var  stubRequestValueErrorFactory
     */
    protected $rveFactory;

    /**
     * constructor
     *
     * @param  stubRequestValueErrorFactory  $rveFactory
     */
    public function __construct(stubRequestValueErrorFactory $rveFactory)
    {
        $this->rveFactory    = $rveFactory;
    }

    /**
     * returns request value error factory
     *
     * @return  stubRequestValueErrorFactory
     */
    public function getRveFactory()
    {
        return $this->rveFactory; 
    }

    /**
     * does the filtering
     *
     * @param   string  $value
     * @return  string
     */
    public function execute($value)
    {
         return $value;
    }
}
?>