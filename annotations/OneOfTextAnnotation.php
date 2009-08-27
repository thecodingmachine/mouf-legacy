<?php
require_once dirname(__FILE__)."/../reflection/MoufAnnotationHelper.php";

/**
 * The @OneOfText annotation.
 * This annotation contains a list of possible values for a property.
 *
 */
class OneOfTextAnnotation 
{
	private $possibleValues;

    public function __construct($value)
    {
        $this->possibleValues = MoufAnnotationHelper::getValueAsList($value);
    }
    
    /**
     * Returns the list of possible values.
     *
     * @return array<string>
     */
    public function getPossibleValues() {
    	return $this->possibleValues;
    }
    
}

?>
