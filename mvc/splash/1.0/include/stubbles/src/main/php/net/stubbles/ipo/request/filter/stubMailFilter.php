<?php
/**
 * Class for filtering mail addresses.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubFilter',
                      'net::stubbles::ipo::request::validator::stubValidator'
);
/**
 * Class for filtering mail addresses.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
class stubMailFilter extends stubBaseObject implements stubFilter
{
    /**
     * request value error factory
     *
     * @var  stubRequestValueErrorFactory
     */
    protected $rveFactory;
    /**
     * validator to use for checking the mail address
     *
     * @var  stubValidator
     */
    protected $mailValidator;

    /**
     * constructor
     *
     * @param  stubRequestValueErrorFactory  $rveFactory     factory to create stubRequestValueErrors
     * @param  stubValidator                 $mailValidator  validator to check the mail address
     */
    public function __construct(stubRequestValueErrorFactory $rveFactory, stubValidator $mailValidator)
    {
        $this->rveFactory    = $rveFactory;
        $this->mailValidator = $mailValidator;
    }

    /**
     * returns the used mail validator
     *
     * @return  stubValidator
     */
    public function getMailValidator()
    {
        return $this->mailValidator;
    }

    /**
     * check if entered passwords fulfill password conditions
     *
     * @param   array|string         $value  the mail addressto check
     * @return  string               the checked mail address to check
     * @throws  stubFilterException  in case $value has errors
     */
    public function execute($value)
    {
        if (strlen($value) === 0) {
            return null;
        }
        
        if ($this->mailValidator->validate($value) === true) {
            return $value;
        }
        
        //    check for spaces
        if (preg_match('/\s/i', $value) != false) {
            throw new stubFilterException($this->rveFactory->create('MAILADDRESS_CANNOT_CONTAIN_SPACES'));
        }

        //    check for German umlaut
        if (preg_match('/[üצהß]/i', $value) != false) {
            throw new stubFilterException($this->rveFactory->create('MAILADDRESS_CANNOT_CONTAIN_UMLAUTS'));
        }

        //    check for more than one '@'
        if (substr_count($value, '@') != 1) {
            throw new stubFilterException($this->rveFactory->create('MAILADDRESS_MUST_CONTAIN_ONE_AT'));
        }

        //    check for valid chars in email
        if (preg_match('/^[' . preg_quote('abcdefghijklmnopqrstuvwxyz1234567890@.+_-') . ']+$/iD', $value) == false) {
            throw new stubFilterException($this->rveFactory->create('MAILADDRESS_CONTAINS_ILLEGAL_CHARS'));
        }
        
        if (strpos($value, '..') !== false) {
            throw new stubFilterException($this->rveFactory->create('MAILADDRESS_CONTAINS_TWO_FOLLOWING_DOTS'));
        }
        
        throw new stubFilterException($this->rveFactory->create('MAILADDRESS_INCORRECT'));
    }
}
?>