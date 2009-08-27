<?php
/**
 * Parser is inside the annotation name
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
stubClassLoader::load('net::stubbles::reflection::annotations::parser::state::stubAnnotationAbstractState',
                      'net::stubbles::reflection::annotations::parser::state::stubAnnotationState'
);
/**
 * Parser is inside the annotation name
 *
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
class stubAnnotationNameState extends stubAnnotationAbstractState implements stubAnnotationState
{
    /**
     * name of the annotation
     *
     * @var  string
     */
    private $name = '';

    /**
     * returns the name of the annotation
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * mark this state as the currently used state
     */
    public function selected()
    {
        parent::selected();
        $this->name = '';
    }

    /**
     * processes a token
     *
     * @param   string  $token
     * @throws  ReflectionException
     */
    public function process($token)
    {
        if (' ' === $token) {
            if (strlen($this->name) == 0) {
                $this->parser->changeState(stubAnnotationState::DOCBLOCK);
                return;
            }
            
            $this->checkName();
            $this->parser->registerAnnotation($this->name);
            $this->parser->changeState(stubAnnotationState::ANNOTATION);
            return;
        }
        
        if ("\n" === $token || "\r" === $token) {
            if (strlen($this->name) > 0) {
                $this->checkName();
                $this->parser->registerAnnotation($this->name);
            }
            
            $this->parser->changeState(stubAnnotationState::DOCBLOCK);
            return;
        }
        
        if ('{' === $token) {
            if (strlen($this->name) == 0) {
                throw new ReflectionException('Annotation name can not be empty');
            }
            
            $this->checkName();
            $this->parser->registerAnnotation($this->name);
            $this->parser->changeState(stubAnnotationState::ARGUMENT);
            return;
        }
        
        if ('[' === $token) {
            if (strlen($this->name) == 0) {
                throw new ReflectionException('Annotation name can not be empty');
            }
            
            $this->checkName();
            $this->parser->registerAnnotation($this->name);
            $this->parser->changeState(stubAnnotationState::ANNOTATION_TYPE);
            return;
        }

        if ('(' === $token) {
            if (strlen($this->name) == 0) {
                throw new ReflectionException('Annotation name can not be empty');
            }
            
            $this->checkName();
            $this->parser->registerAnnotation($this->name);
            $this->parser->changeState(stubAnnotationState::PARAMS);
            return;
        }
        
        $this->name .= $token;
    }

    /**
     * check if the name is valid
     *
     * @throws  ReflectionException
     */
    protected function checkName()
    {
        if (preg_match('/^[a-zA-Z_]{1}[a-zA-Z_0-9]*$/', $this->name) == false) {
            throw new ReflectionException('Annotation parameter name may contain letters, underscores and numbers, but contains an invalid character.');
        }
    }
}
?>