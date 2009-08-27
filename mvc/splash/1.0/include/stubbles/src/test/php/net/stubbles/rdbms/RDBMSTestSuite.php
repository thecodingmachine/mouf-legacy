<?php
/**
 * Test suite for all rdbms classes.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test
 */
/**
 * Test suite for all rdbms classes.
 *
 * @package     stubbles
 * @subpackage  test
 */
class RDBMSTestSuite extends PHPUnit_Framework_TestSuite
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
        $suite->addTestFile($dir . '/stubDatabaseConnectionDataTestCase.php');
        $suite->addTestFile($dir . '/stubDatabaseConnectionPoolTestCase.php');
        $suite->addTestFile($dir . '/stubDatabaseConnectionProviderTestCase.php');
        $suite->addTestFile($dir . '/stubDatabaseXJConfInitializerTestCase.php');
        
        // criteria
        $suite->addTestFile($dir . '/criteria/stubAndCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubEqualCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubGreaterEqualCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubGreaterThanCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubInCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubLessEqualCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubLessThanCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubLikeCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubNegateCriterionTestCase.php');
        $suite->addTestFile($dir . '/criteria/stubOrCriterionTestCase.php');
        
        // pdo
        $suite->addTestFile($dir . '/pdo/stubDatabasePDOConnectionTestCase.php');
        $suite->addTestFile($dir . '/pdo/stubDatabasePDOStatementTestCase.php');
        
        // persistence
        $suite->addTestFile($dir . '/persistence/stubSetterMethodHelperTestCase.php');
        $suite->addTestFile($dir . '/persistence/annotation/stubEntityAnnotationTestCase.php');
        $suite->addTestFile($dir . '/persistence/creator/stubDatabaseCreatorTestCase.php');
        $suite->addTestFile($dir . '/persistence/eraser/stubDatabaseEraserTestCase.php');
        $suite->addTestFile($dir . '/persistence/finder/stubDatabaseFinderTestCase.php');
        $suite->addTestFile($dir . '/persistence/serializer/stubDatabaseSerializerTestCase.php');
        
        // querybuilder
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseMySQLQueryBuilderTestCase.php');
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseQueryBuilderFactoryTestCase.php');
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseSelectTestCase.php');
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseTableColumnTestCase.php');
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseTableDescriptionTestCase.php');
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseTableJoinTestCase.php');
        $suite->addTestFile($dir . '/querybuilder/stubDatabaseTableRowTestCase.php');
        return $suite;
    }
}
?>