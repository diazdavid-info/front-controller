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
use PHPUnit_Framework_MockObject_MockObject;

class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test:    Call specific method and class
     * Given:   Path URL
     * Return:  Hello from method add class FakeController
     * When:    Always
     */
    public function testCallSpecificMethodAndClassGivenPathUrlReturnHelloWhenAlways()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController'], 'default' => 'index@Fake\FakeController'];

        $mock = $this->createMock($arrayConfiguration, 'Fake\FakeController', 'add', []);

        $frontController = new FrontController($mock);
        $result = $frontController->init('/');
        $this->assertTrue($result === 'Hello from method add class FakeController');
    }

    /**
     * @param $arrayConfiguration
     * @param $class
     * @param $method
     * @param array $parameters
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function createMock($arrayConfiguration, $class, $method, array $parameters)
    {
        $mock = $this->getMockBuilder('FrontController\Reader\ReaderConfiguration')
            ->setConstructorArgs([$arrayConfiguration])->getMock();
        $mock->method('getClass')->will($this->returnValue($class));
        $mock->method('getMethod')->will($this->returnValue($method));
        $mock->method('getParameters')->will($this->returnValue($parameters));
        return $mock;
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

        $mock = $this->createMock($arrayConfiguration, 'Fake\FakeController', 'index', []);

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
            ->setConstructorArgs([$arrayConfiguration])->getMock();
        $mock->method('getClass')->will($this->throwException(new ClassNotFoundException()));
        $mock->method('getMethod')->will($this->returnValue('add'));
        $mock->method('getParameters')->will($this->returnValue([]));

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
            ->setConstructorArgs([$arrayConfiguration])->getMock();
        $mock->method('getClass')->will($this->returnValue('Fake\FakeController'));
        $mock->method('getMethod')->will($this->throwException(new MethodNotFoundException()));
        $mock->method('getParameters')->will($this->returnValue([]));

        $frontController = new FrontController($mock);
        $frontController->init('/');
    }

    /**
     * Test:    getParameters
     * Given:   Path URL
     * Return:  param: 2
     * When:    Exist parameter in path url
     */
    public function testGetParametersGivenPathUrlReturnParameterWhenExistParameterInPathUrl()
    {
        $arrayConfiguration = ['router' => ['/{id}' => 'param@Fake\FakeController:id']];

        $mock = $this->createMock($arrayConfiguration, 'Fake\FakeController', 'param', ['id' => 2]);

        $frontController = new FrontController($mock);
        $result = $frontController->init('/2');
        $this->assertTrue($result === 'param: 2');
    }

    /**
     * Test:    getParameters
     * Given:   Path URL
     * Return:  param: 2, 4
     * When:    Exist parameters in path url
     */
    public function testGetParametersGivenPathUrlReturnListParametersWhenExistParametersInPathUrl()
    {
        $arrayConfiguration = ['router' => ['/{id}/school/{idSchool}' => 'params@Fake\FakeController:id:idSchool']];

        $mock = $this->createMock($arrayConfiguration, 'Fake\FakeController', 'params', ['id' => 2, 'idSchool' => 4]);

        $frontController = new FrontController($mock);
        $result = $frontController->init('/2/school/4');
        $this->assertTrue($result === 'params: 2, 4');
    }


}
