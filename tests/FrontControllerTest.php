<?php
/**
 * Test to FrontController
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 10:54
 */

namespace frontController\test;

use frontController\exceptions\ClassNotFoundException;
use frontController\exceptions\MethodNotFoundException;
use frontController\FrontController;
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
        $arrayConfiguration = ['router' => ['/' => 'add@frontController\test\fakes\FakeController'], 'default' => 'index@frontController\test\fakes\FakeController'];

        $mock = $this->createMock($arrayConfiguration, 'frontController\test\fakes\FakeController', 'add', []);

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
        $mock = $this->getMockBuilder('frontController\readers\ReaderConfiguration')
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
        $arrayConfiguration = ['router' => ['/' => 'add@frontController\test\fakes\FakeController'], 'default' => 'index@frontController\test\fakes\FakeController'];

        $mock = $this->createMock($arrayConfiguration, 'frontController\test\fakes\FakeController', 'index', []);

        $frontController = new FrontController($mock);
        $result = $frontController->init('/a');
        $this->assertTrue($result === 'Default');
    }

    /**
     * Test:    Throw Exception
     * Given:   Path URL
     * Return:  Throw ClassNotFoundException
     * When:    Not exist class and default class
     * @expectedException \frontController\exceptions\ClassNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenNotExistClassAndNotExistDefaultClass()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController2']];

        $mock = $this->getMockBuilder('frontController\readers\ReaderConfiguration')
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
     * @expectedException \frontController\exceptions\MethodNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenNotExistMethodAndNotExistDefaultMethod()
    {
        $arrayConfiguration = ['router' => ['/' => 'add2@frontController\test\fakes\FakeController']];

        $mock = $this->getMockBuilder('frontController\readers\ReaderConfiguration')
            ->setConstructorArgs([$arrayConfiguration])->getMock();
        $mock->method('getClass')->will($this->returnValue('frontController\test\fakes\FakeController'));
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
        $arrayConfiguration = ['router' => ['/{id}' => 'param@frontController\test\fakes\FakeController:id']];

        $mock = $this->createMock($arrayConfiguration, 'frontController\test\fakes\FakeController', 'param', ['id' => 2]);

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
        $arrayConfiguration = ['router' => ['/{id}/school/{idSchool}' => 'params@frontController\test\fakes\FakeController:id:idSchool']];

        $mock = $this->createMock($arrayConfiguration, 'frontController\test\fakes\FakeController', 'params', ['id' => 2, 'idSchool' => 4]);

        $frontController = new FrontController($mock);
        $result = $frontController->init('/2/school/4');
        $this->assertTrue($result === 'params: 2, 4');
    }


}
