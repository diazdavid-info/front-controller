<?php
/**
 * Test to FrontController
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 10:54
 */

namespace Test;


use FrontController\Exception\ClassNotFoundException;
use FrontController\Exception\MethodNotFoundException;
use FrontController\FrontController\FrontController;
use FrontController\Reader\ReaderConfiguration;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FrontController
     */
//    private $_frontController;
    /**
     * @var ReaderConfiguration
     */
//    private $_mockReaderConfiguration;

    /**
     * This method is called before a test is executed.
     */
//    protected function setUp()
//    {
//
//        $this->_mockReaderConfiguration = $this->getMockBuilder('FrontController\Reader\ReaderConfiguration');
//
//        $this->_frontController = new FrontController($this->_mockReaderConfiguration);
//    }

    /**
     * Test:    Call specific method and class
     * Given:   Path URL
     * Return:  Hello from method add class FakeController
     * When:    Always
     */
    public function testCallSpecificMethodAndClassGivenPathUrlReturnHelloWhenAlways()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController'], 'default' => 'index@Fake\FakeController'];

        $mock = $this->getMockBuilder('FrontController\Reader\ReaderConfiguration')
            ->setConstructorArgs([$arrayConfiguration])->setMethods(['getMethod', 'getClass'])->getMock();
        $mock->method('getClass')->will($this->returnValue('Fake\FakeController'));
        $mock->method('getMethod')->will($this->returnValue('add'));

        $frontController = new FrontController($mock);
        $result = $frontController->init('/');
        $this->assertTrue($result === 'Hello from method add class FakeController');
    }

    /**
     * Test:    Call default method and class
     * Given:   Path URL
     * Return:  Default
     * When:    Always
     */
    public function testCallDefaultMethodAndClassGivenPathUrlAndAReturnDefaultWhenNotExistConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController'], 'default' => 'index@Fake\FakeController'];

        $mock = $this->getMockBuilder('FrontController\Reader\ReaderConfiguration')
            ->setConstructorArgs([$arrayConfiguration])->setMethods(['getMethod', 'getClass'])->getMock();
        $mock->method('getClass')->will($this->returnValue('Fake\FakeController'));
        $mock->method('getMethod')->will($this->returnValue('index'));

        $frontController = new FrontController($mock);
        $result = $frontController->init('/a');
        $this->assertTrue($result === 'Default');
    }

    /**
     * Test:    Throw Exception
     * Given:   Path URL
     * Return:  Throw ClassNotFoundException
     * When:    Not exist class and default class
     * @expectedException \FrontController\Exception\ClassNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenNotExistClassAndNotExistDefaultClass()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController2']];

        $mock = $this->getMockBuilder('FrontController\Reader\ReaderConfiguration')
            ->setConstructorArgs([$arrayConfiguration])->setMethods(['getMethod', 'getClass'])->getMock();
        $mock->method('getClass')->will($this->throwException(new ClassNotFoundException()));
        $mock->method('getMethod')->will($this->returnValue('add'));

        $frontController = new FrontController($mock);
        $frontController->init('/');
    }

    /**
     * Test:    Throw Exception
     * Given:   Path URL
     * Return:  Throw ClassNotFoundException
     * When:    Not exist method and default method
     * @expectedException \FrontController\Exception\MethodNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenNotExistMethodAndNotExistDefaultMethod()
    {
        $arrayConfiguration = ['router' => ['/' => 'add2@Fake\FakeController']];

        $mock = $this->getMockBuilder('FrontController\Reader\ReaderConfiguration')
            ->setConstructorArgs([$arrayConfiguration])->setMethods(['getMethod', 'getClass'])->getMock();
        $mock->method('getClass')->will($this->returnValue('Fake\FakeController'));
        $mock->method('getMethod')->will($this->throwException(new MethodNotFoundException()));

        $frontController = new FrontController($mock);
        $frontController->init('/');
    }


}
