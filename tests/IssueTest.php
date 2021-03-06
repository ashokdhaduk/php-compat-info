<?php
/**
 * Unit tests for PHP_CompatInfo package, issues reported
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    All sources used are Licensed : BSD or public domain
 * @version    SVN: $Id$
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 2.0.0beta3
 */

if (!defined('TEST_FILES_PATH')) {
    define(
        'TEST_FILES_PATH',
        dirname(__FILE__) . DIRECTORY_SEPARATOR .
        '_files' . DIRECTORY_SEPARATOR
    );
}

/**
 * Tests for the PHP_CompatInfo class known issues
 *
 * @category   PHP
 * @package    PHP_CompatInfo
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    All sources used are Licensed : BSD or public domain
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class PHP_CompatInfo_IssueTest extends PHPUnit_Framework_TestCase
{
    protected $pci;

    /**
     * Sets up the fixture.
     *
     * @return void
     */
    protected function setUp()
    {
        $options = array(
            'cacheDriver' => 'null',
        );

        $this->pci = new PHP_CompatInfo($options);
    }

    /**
     * Regression test for bug #1626
     *
     * @link http://pear.php.net/bugs/bug.php?id=1626
     *       Class calls are seen wrong
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getExtensions
     * @return void
     */
    public function testBug1626()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source1626.php');

        $extensions = $this->pci->getExtensions();

        $this->assertSame(
            array(), $extensions
        );
    }

    /**
     * Regression test for bug #6581
     *
     * @link http://pear.php.net/bugs/bug.php?id=6581
     *       Functions missing in func_array.php
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testBug6581()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source6581.php');

        $this->assertSame(
            array('4.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug #8559
     *
     * @link http://pear.php.net/bugs/bug.php?id=8559
     *       PHP_CompatInfo fails to scan if it finds empty file in path
     * @covers PHP_CompatInfo::parse
     * @return void
     */
    public function testBug8559()
    {
        $this->assertFalse(
            $this->pci->parse(
                TEST_FILES_PATH . DIRECTORY_SEPARATOR . 'emptyDir'
            )
        );
    }

    /**
     * Regression test for bug #10100
     *
     * @link http://pear.php.net/bugs/bug.php?id=10100
     *       Wrong parsing of possible attributes in strings
     * @covers PHP_CompatInfo::parse
     * @return void
     */
    public function testBug10100()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source10100.php');

        $this->assertSame(
            array('4.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug #13417
     *
     * @link http://pear.php.net/bugs/bug.php?id=13417
     *       Parser ignore class-method that are named as standard php functions
     * @covers PHP_CompatInfo::parse
     * @return void
     */
    public function testBug13417()
    {
        // HTML_CSS-1.5.1 package and HTML_CSS_Error Class
        $this->pci->parse(TEST_FILES_PATH . 'source13417.php');

        $this->assertSame(
            array('4.3.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug #13568
     *
     * @link http://pear.php.net/bugs/bug.php?id=13568
     *       User functions are not ignored
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getExtensions
     * @return void
     */
    public function testBug13568()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source13568.php');

        $this->assertSame(
            array(), $this->pci->getExtensions()
        );
    }

    /**
     * Regression test for bug #14696
     *
     * @link http://pear.php.net/bugs/bug.php?id=14696
     *       PHP_CompatInfo fails to scan code line when not ended with semicolon
     * @covers PHP_CompatInfo::parse
     * @return void
     */
    public function testBug14696()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source14696.php');

        $this->assertSame(
            array('4.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug #16302
     *
     * @link http://pear.php.net/bugs/bug.php?id=16302
     *       Exception class is detected as 4.0.0 code
     * @covers PHP_CompatInfo::parse
     * covers PHP_CompatInfo::getClasses
     * @return void
     */
    public function testBug16302()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source16302.php');

        $classes = $this->pci->getClasses('user');

        $expected = array(
            'Foo_Exception' => array(
                'versions' => array('5.1.0', ''),
                'uses' => 1,
                'sources' => array(TEST_FILES_PATH . 'source16302.php'),
                'excluded' => false,
            ),
        );
        $this->assertSame(
            $expected, $classes
        );
    }

    /**
     * Regression test for request #6056
     *
     * @link http://pear.php.net/bugs/bug.php?id=6056
     *       Add support for reporting max PHP version
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testRequest6056()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source6056.php');

        $this->assertSame(
            array('5.1.0', '5.0.4'), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug #18881
     *
     * @link http://pear.php.net/bugs/bug.php?id=18881
     *       Parameter count isn't recognized
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testBug18881()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source18881.php');

        $this->assertSame(
            array('5.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for request #13094
     *
     * @link http://pear.php.net/bugs/bug.php?id=13094
     *       PHP5 method chaining
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testRequest13094()
    {
        $this->pci->parse(TEST_FILES_PATH . 'source13094.php');

        $this->assertSame(
            array('5.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug GH-26
     *
     * @link https://github.com/llaville/php-compat-info/issues/26
     *       Mistake in classMemberAccessOnInstantiation detection
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testBugGH26()
    {
        $this->pci->parse(TEST_FILES_PATH . 'gh26.php');

        $this->assertSame(
            array('5.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug GH-27
     *
     * @link https://github.com/llaville/php-compat-info/issues/27
     *       Mistake in arrayDereferencing detection
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testBugGH27()
    {
        $this->pci->parse(TEST_FILES_PATH . 'gh27.php');

        $this->assertSame(
            array('4.0.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug GH-28
     *
     * @link https://github.com/llaville/php-compat-info/issues/28
     *       Mistake in classMemberAccessOnInstantiation detection
     * @covers PHP_CompatInfo::parse
     * @covers PHP_CompatInfo::getVersions
     * @return void
     */
    public function testBugGH28()
    {
        $this->pci->parse(TEST_FILES_PATH . 'gh28.php');

        $this->assertSame(
            array('5.1.0', ''), $this->pci->getVersions()
        );
    }

    /**
     * Regression test for bug GH-29
     *
     * @link https://github.com/llaville/php-compat-info/issues/29
     *       Inconsistent magic constants detection on PHP 5.2, 5.3 and 5.4
     * @link http://www.php.net/manual/en/language.constants.predefined.php
     * @covers PHP_CompatInfo::parse
     *  covers PHP_CompatInfo::getConstants
     * @return void
     */
    public function testBugGH29()
    {
        $this->pci->parse(TEST_FILES_PATH . 'gh29.php');

        $constantsPredefined = array_keys(
            $this->pci->getConstants('Core', '^__(.*)__$')
        );
        sort($constantsPredefined);

        $this->assertSame(
            array(
                '__CLASS__',
                '__DIR__',
                '__FILE__',
                '__FUNCTION__',
                '__LINE__',
                '__METHOD__',
                '__NAMESPACE__',
                '__TRAIT__',
            ),
            $constantsPredefined
        );
    }

    /**
     * Regression test for request GH-30
     *
     * @link https://github.com/llaville/php-compat-info/issues/30
     *       mb_ereg_replace_callback support for PHP 5.4.1
     * @covers PHP_CompatInfo::parse
     * @return void
     */
    public function testRequestGH30()
    {
        $this->pci->parse(TEST_FILES_PATH . 'gh30-1.php');

        $this->assertSame(
            array('5.4.1-dev', ''), $this->pci->getVersions()
        );
    }
}
