<?php
/**
 * Test to FrontController
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 10:54
 */

namespace Test;


use FrontController\FrontController\FrontController;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FrontController
     */
    private $_frontController;

    /**
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController'], 'default' => 'index@Fake\FakeController'];
        $this->_frontController = new FrontController($arrayConfiguration);
    }

    /**
     * Test:    Call specific method and class
     * Given:   Path URL and array configuration
     * Return:  Hello from method add class FakeController
     * When:    Always
     */
    public function testCallSpecificMethodAndClassGivenPathUrlAndArrayConfReturnHelloWhenAlways()
    {
        $result = $this->_frontController->init('/');
        $this->assertTrue($result === 'Hello from method add class FakeController');
    }

    /**
     * Test:    Call default method and class
     * Given:   Path URL and array configuration
     * Return:  Default
     * When:    Always
     */
    public function testCallDefaultMethodAndClassGivenPathUrlAndArrayConfReturnDefaultWhenNotExistConfig()
    {
        $result = $this->_frontController->init('/a');
        $this->assertTrue($result === 'Default');
    }


}
