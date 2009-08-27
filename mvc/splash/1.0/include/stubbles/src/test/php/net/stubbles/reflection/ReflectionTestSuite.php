<?php
/**
 * Test suite for all helper classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all helper classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class ReflectionTestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * returns the test suite to be run
     *
     * @return  PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new self();
        $dir   = dirname(__FILE__);
        // annotations
        $suite->addTestFile($dir . '/annotations/stubAnnotationFactoryApplicableTestCase.php');
        $suite->addTestFile($dir . '/annotations/stubAnnotationFactoryBuildTestCase.php');
        $suite->addTestFile($dir . '/annotations/stubAnnotationFactoryTestCase.php');
        
        // parser
        $suite->addTestFile($dir . '/annotations/parser/stubAnnotationStateParserTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationAnnotationStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationArgumentStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationDocblockStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationNameStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationParamNameStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationParamsStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationParamValueStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationTextStateTestCase.php');
        $suite->addTestFile($dir . '/annotations/parser/state/stubAnnotationTypeStateTestCase.php');
        
        // default reflection package
        $suite->addTestFile($dir . '/stubReflectionClassTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionExtensionTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionFunctionTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionMethodTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionObjectTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionPackageTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionParameterTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionPropertyTestCase.php');
        $suite->addTestFile($dir . '/stubReflectionPrimitiveTestCase.php');
        return $suite;
    }
}
?>