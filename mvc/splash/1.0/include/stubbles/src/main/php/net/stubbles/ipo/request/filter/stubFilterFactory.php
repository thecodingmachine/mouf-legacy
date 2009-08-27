<?php
/**
 * Factory to create filters.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequestValueErrorFactory',
                      'net::stubbles::ipo::request::filter::stubAbstractFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubStrategyFilterDecorator',
                      'net::stubbles::ipo::request::filter::provider::stubFilterProvider',
                      'net::stubbles::ipo::request::filter::provider::stubMailFilterProvider',
                      'net::stubbles::ipo::request::filter::provider::stubSimpleFilterProvider',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::lang::exceptions::stubMethodNotSupportedException',
                      'net::stubbles::lang::types::stubDate'
);
/**
 * Factory to create filters.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter
 */
class stubFilterFactory extends stubAbstractFilterDecorator
{
    /**
     * list of provider to use
     *
     * @var  array<stubFilterProvider>
     */
    protected static $provider = array();
    /**
     * the request error value factory to be used by the filter
     *
     * @var  stubRequestErrorValueFactory
     */
    protected $rveFactory;

    /**
     * static initializer
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$provider[] = new stubSimpleFilterProvider(array('int', 'integer'), 'net::stubbles::ipo::request::filter::stubIntegerFilter');
        self::$provider[] = new stubSimpleFilterProvider(array('double', 'float'), 'net::stubbles::ipo::request::filter::stubFloatFilter');
        self::$provider[] = new stubSimpleFilterProvider(array('string'), 'net::stubbles::ipo::request::filter::stubStringFilter');
        self::$provider[] = new stubSimpleFilterProvider(array('text'), 'net::stubbles::ipo::request::filter::stubTextFilter');
        self::$provider[] = new stubSimpleFilterProvider(array('password'), 'net::stubbles::ipo::request::filter::stubPasswordFilter');
        self::$provider[] = new stubSimpleFilterProvider(array('http'), 'net::stubbles::ipo::request::filter::stubHTTPURLFilter');
        self::$provider[] = new stubSimpleFilterProvider(array('date'), 'net::stubbles::ipo::request::filter::stubDateFilter');
        self::$provider[] = new stubMailFilterProvider();
    }
    // @codeCoverageIgnoreEnd

    /**
     * adds a user-defined filter provider
     *
     * @param  stubFilterProvider  $filterProvider
     */
    public static function addFilterProvider(stubFilterProvider $filterProvider)
    {
        self::$provider[] = $filterProvider;
    }

    /**
     * removes a filter provider
     *
     * @param  string  $type
     */
    public static function removeFilterProvider($type)
    {
        foreach (self::$provider as $key => $filterProvider) {
            if ($filterProvider->isResponsible($type) === true) {
                unset(self::$provider[$key]);
            }
        }
    }

    /**
     * constructor
     *
     * @param  stubFilter  $filter
     */
    public function __construct(stubFilter $filter)
    {
        $this->setDecoratedFilter($filter);
    }

    /**
     * creates a filter for the given type
     *
     * @param   string             $type  type of filter to create
     * @param   array              $args  optional  constructor arguments for filter
     * @return  stubFilterFactory
     * @throws  stubIllegalArgumentException
     */
    public static function forType($type, array $args = null)
    {
        $filter = null;
        foreach (self::$provider as $filterProvider) {
            if ($filterProvider->isResponsible($type) === true) {
                $filter = $filterProvider->getFilter($args);
            }
        }
        
        if (null === $filter) {
            throw new stubIllegalArgumentException('No filter known for given type.');
        }
        
        $me = new self($filter);
        if (isset($args[0]) === true && $args[0] instanceof stubRequestValueErrorFactory) {
            $me->using($args[0]);
        }
        
        return $me;
    }

    /**
     * convenience method to allow method chaining for already existing filters
     *
     * @param   stubFilter  $filter
     * @return  stubFilterFactory
     */
    public static function forFilter(stubFilter $filter)
    {
        return new self($filter);
    }

    /**
     * sets the request error value factory to be used by the filter
     *
     * @param   stubRequestValueErrorFactory  $rveFactory
     * @return  stubFilterFactory
     */
    public function using(stubRequestValueErrorFactory $rveFactory)
    {
        $this->rveFactory = $rveFactory;
        return $this;
    }

    /**
     * returns the request error value factory to be used by the filter
     *
     * @return  stubRequestValueErrorFactory
     * @throws  stubRuntimeException
     */
    public function getRVEFactory()
    {
        if (null === $this->rveFactory) {
            $class = stubRegistry::getConfig('net.stubbles.ipo.request.valueerrorfactory.class', 'net::stubbles::ipo::request::stubRequestValueErrorXJConfFactory');
            $classname = stubClassLoader::getNonQualifiedClassName($class);
            if (class_exists($classname) === false) {
                stubClassLoader::load($class);
            }
            
            $rveFactory = new $classname();
            if (($rveFactory instanceof stubRequestValueErrorFactory) === false) {
                throw new stubRuntimeException('Configured net.stubbles.ipo.request.valueerrorfactory.class is not an instance of net::stubbles::ipo::request::stubRequestValueErrorFactory.');
            }
            
            $this->rveFactory = $rveFactory;
        }
        
        return $this->rveFactory;
    }

    /**
     * decorates the filter with a range filter
     *
     * To create a lower border only use NULL for $max, to create an upper
     * border only use NULL for $min.
     *
     * @param   numeric            $min
     * @param   numeric            $max
     * @param   string             $minErrorId  optional  error id for failing min validation
     * @param   string             $maxErrorId  optional  error id for failing max validation
     * @param   int                $strategy    optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function inRange($min, $max, $minErrorId = null, $maxErrorId = null, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        if (null !== $min || null !== $max) {
            stubClassLoader::load('net::stubbles::ipo::request::filter::stubRangeFilterDecorator',
                                  'net::stubbles::ipo::request::validator::stubMinNumberValidator',
                                  'net::stubbles::ipo::request::validator::stubMaxNumberValidator'
            );
            $filter = new stubRangeFilterDecorator($this->getDecoratedFilter(), $this->getRVEFactory());
            if (null !== $min) {
                $filter->setMinValidator(new stubMinNumberValidator($min), $minErrorId);
            }
            
            if (null !== $max) {
                $filter->setMaxValidator(new stubMaxNumberValidator($max), $maxErrorId);
            }
            
            $filter->setStrategy($strategy);
            $this->setDecoratedFilter($filter);
        }
        
        return $this;
    }

    /**
     * decorates the filter with a length filter
     *
     * To create a lower border only use NULL for $maxLength, to create an upper
     * border only use NULL for $minLength.
     *
     * @param   numeric            $minLength
     * @param   numeric            $maxLength
     * @param   string             $minLengthErrorId  optional  error id for failing min validation
     * @param   string             $maxLengthErrorId  optional  error id for failing max validation
     * @param   int                $strategy          optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function length($minLength, $maxLength, $minLengthErrorId = null, $maxLengthErrorId = null, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        if (null !== $minLength || null !== $maxLength) {
            stubClassLoader::load('net::stubbles::ipo::request::filter::stubLengthFilterDecorator',
                                  'net::stubbles::ipo::request::validator::stubMinLengthValidator',
                                  'net::stubbles::ipo::request::validator::stubMaxLengthValidator'
            );
            $filter = new stubLengthFilterDecorator($this->getDecoratedFilter(), $this->getRVEFactory());
            if (null !== $minLength) {
                $filter->setMinLengthValidator(new stubMinLengthValidator($minLength), $minLengthErrorId);
            }
            
            if (null !== $maxLength) {
                $filter->setMaxLengthValidator(new stubMaxLengthValidator($maxLength), $maxLengthErrorId);
            }
            
            $filter->setStrategy($strategy);
            $this->setDecoratedFilter($filter);
        }
        
        return $this;
    }

    /**
     * decorates the filter with a period filter
     *
     * @param   stubDate           $minDate         optional
     * @param   stubDate           $maxDate         optional
     * @param   string             $minDateErrorId  optional  error id for failing min validation
     * @param   string             $maxDateErrorId  optional  error id for failing max validation
     * @param   string             $dateFormat      optional  format of date in error messages
     * @return  stubFilterFactory
     */
    public function inPeriod(stubDate $minDate = null, stubDate $maxDate = null, $minDateErrorId = null, $maxDateErrorId = null, $dateFormat = null)
    {
        if (null !== $minDate || null !== $maxDate) {
            stubClassLoader::load('net::stubbles::ipo::request::filter::stubPeriodFilterDecorator');
            $filter = new stubPeriodFilterDecorator($this->getDecoratedFilter(), $this->getRVEFactory());
            if (null !== $minDate) {
                $filter->setMinDate($minDate, $minDateErrorId);
            }
            
            if (null !== $maxDate) {
                $filter->setMaxDate($maxDate, $maxDateErrorId);
            }
            
            if (null != $dateFormat) {
                $filter->setDateFormat($dateFormat);
            }
            
            $this->setDecoratedFilter($filter);
        }
        
        return $this;
    }

    /**
     * decorates the filter as required
     *
     * @param   string             $errorId   optional
     * @param   int                $strategy  optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function asRequired($errorId = null, $strategy = stubStrategyFilterDecorator::STRATEGY_BEFORE)
    {
        stubClassLoader::load('net::stubbles::ipo::request::filter::stubRequiredFilterDecorator');
        $filter = new stubRequiredFilterDecorator($this->getDecoratedFilter(), $this->getRVEFactory());
        if (null != $errorId) {
            $filter->setErrorId($errorId);
        }
        
        $filter->setStrategy($strategy);
        $this->setDecoratedFilter($filter);
        return $this;
    }

    /**
     * decorates the filter with a default value
     *
     * @param   mixed              $defaultValue
     * @param   int                $strategy      optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function defaultsTo($defaultValue, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        stubClassLoader::load('net::stubbles::ipo::request::filter::stubDefaultValueFilterDecorator');
        $filter = new stubDefaultValueFilterDecorator($this->getDecoratedFilter(), $defaultValue);
        $filter->setStrategy($strategy);
        $this->setDecoratedFilter($filter);
        return $this;
    }

    /**
     * decorates the filter with a validator
     *
     * @param   stubValidator      $validator
     * @param   string             $errorId    optional
     * @param   int                $strategy   optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function validatedBy(stubValidator $validator, $errorId = null, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        stubClassLoader::load('net::stubbles::ipo::request::filter::stubValidatorFilterDecorator');
        $filter = new stubValidatorFilterDecorator($this->getDecoratedFilter(), $this->getRVEFactory(), $validator);
        if (null != $errorId) {
            $filter->setErrorId($errorId);
        }
        
        $filter->setStrategy($strategy);
        $this->setDecoratedFilter($filter);
        return $this;
    }

    /**
     * decorates the filter with an encoder
     *
     * @param   stubStringEncoder  $encoder
     * @param   int                $strategy  optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function encodedWith(stubStringEncoder $encoder, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        $this->codedWith($encoder, stubStringEncoder::MODE_ENCODE, $strategy);
        return $this;
    }

    /**
     * decorates the filter with a decoder
     *
     * @param   stubStringEncoder  $encoder
     * @param   int                $strategy  optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    public function decodedWith(stubStringEncoder $encoder, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        $this->codedWith($encoder, stubStringEncoder::MODE_DECODE, $strategy);
        return $this;
    }

    /**
     * decorates the filter with an encoder
     *
     * @param   stubStringEncoder  $encoder
     * @param   int                $encoderMode  optional
     * @param   int                $strategy     optional  strategy to be used: before or after decorated filter
     * @return  stubFilterFactory
     */
    protected function codedWith(stubStringEncoder $encoder, $encoderMode = stubStringEncoder::MODE_DECODE, $strategy = stubStrategyFilterDecorator::STRATEGY_AFTER)
    {
        stubClassLoader::load('net::stubbles::ipo::request::filter::stubEncodingFilterDecorator');
        $filter = new stubEncodingFilterDecorator($this->getDecoratedFilter(), $encoder, $encoderMode);
        $filter->setStrategy($strategy);
        $this->setDecoratedFilter($filter);
    }

    /**
     * interceptor for method calls on filters without direct support
     *
     * @param   string             $method     name of the method to call
     * @param   array              $arguments  list of arguments for the method
     * @return  stubFilterFactory
     * @throws  stubMethodNotSupportedException
     */
    public function __call($method, $arguments)
    {
        if ($this->callRecursive($this->getDecoratedFilter(), $method, $arguments) === true) {
            return $this;
        }
        
        throw new stubMethodNotSupportedException('The method ' . $method . ' is not supported by the current filter.');
    }

    /**
     * helper method to recurse down to the base filter in order to call a method
     *
     * @param   stubFilter  $filter     filter to try to call the method off
     * @param   string      $method     name of the method to call
     * @param   array       $arguments  list of arguments for the method
     * @return  bool        true if the call was successful, else false
     */
    protected function callRecursive(stubFilter $filter, $method, $arguments)
    {
        if (method_exists($filter, $method) === true) {
            call_user_func_array(array($filter, $method), $arguments);
            return true;
        }
        
        if (method_exists($filter, 'getDecoratedFilter') === true) {
            return $this->callRecursive($filter->getDecoratedFilter(), $method, $arguments);
        }
        
        return false;
    }

    /**
     * execute the filter
     *
     * @param   mixed  $value  value to filter
     * @return  mixed
     */
    public function execute($value)
    {
        return $this->getDecoratedFilter()->execute($value);
    }
}
?>