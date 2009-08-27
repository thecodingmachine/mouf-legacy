<?php
/**
 * Abstract base class for annotion parser states
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
/**
 * Abstract base class for annotion parser states
 *
 * @package     stubbles
 * @subpackage  reflection_annotations_parser_state
 */
abstract class stubAnnotationAbstractState extends stubBaseObject
{
    /**
     * the parser this state belongs to
     *
     * @var  stubAnnotationParser
     */
    protected $parser;

    /**
     * constructor
     *
     * @param  stubAnnotationParser  $parser
     */
    public function __construct(stubAnnotationParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * mark this state as the currently used state
     */
    public function selected()
    {
        // intentionally empty
    }
}
?>