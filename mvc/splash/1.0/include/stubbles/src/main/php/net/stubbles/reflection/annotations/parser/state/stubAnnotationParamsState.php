<?php
/**
 * Parser is inside the annotation params
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
 * Parser is inside the annotation params
 *
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
class stubAnnotationParamsState extends stubAnnotationAbstractState implements stubAnnotationState
{
    /**
     * list of tokens that lead to no actions in this state
     * 
     * @var  array<string>
     */
    protected $doNothingTokens = array(',', ' ', "\r", "\n", "\t", '*');

    /**
     * processes a token
     *
     * @param  string  $token
     */
    public function process($token)
    {
        if (')' === $token) {
            $this->parser->changeState(stubAnnotationState::DOCBLOCK);
            return;
        }
        
        if (in_array($token, $this->doNothingTokens) == true) {
            return;
        }
        
        $this->parser->changeState(stubAnnotationState::PARAM_NAME, $token);
    }
}
?>