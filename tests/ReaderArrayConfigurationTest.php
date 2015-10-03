<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 27/9/15
 * Time: 10:36
 */

namespace Fake;


use FrontController\Reader\ReaderArrayConfiguration;

class ReaderArrayConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test:    get method
     * Given:   path url
     * Return:  name method
     * When:    the method is specified in array config
     */
    public function testGetMethodGivenPathUrlReturnNameMethodWhenTheMethodIsSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController']];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $method = $reader->getMethod('/');
        $this->assertTrue($method === 'add');
    }

    /**
     * Test:    get method default
     * Given:   path url
     * Return:  name method default
     * When:    the method is not specified in array config
     */
    public function testGetMethodDefaultGivenPathUrlReturnNameMethodDefaultWhenTheMethodIsNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController'], 'default' => 'index@Fake\FakeController'];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $methodDefault = $reader->getMethod('/add');
        $this->assertTrue($methodDefault === 'index');
    }

    /**
     * Test:    throw exception
     * Given:   path url
     * Return:  Throw Exception
     * When:    the method and method default are not in array config
     * @expectedException \FrontController\Exception\MethodNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenTheMethodAndMethodDefaultAreNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => []];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $reader->getMethod('/');
    }

    /**
     * Test:    get class
     * Given:   path url
     * Return:  name class
     * When:    the class is specified in array config
     */
    public function testGetClassGivenPathUrlReturnNameClassWhenTheClassIsSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController']];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $class = $reader->getClass('/');
        $this->assertTrue($class == 'Fake\FakeController');
    }

    /**
     * Test:    get class default
     * Given:   path url
     * Return:  name class default
     * When:    the class is not specified in array config
     */
    public function testGetClassDefaultGivenPathUrlReturnNameClassDefaultWhenTheClassIsNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@Fake\FakeController'], 'default' => 'index@Fake\FakeDefaultController'];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $classDefault = $reader->getClass('/add');
        $this->assertTrue($classDefault == 'Fake\FakeDefaultController');
    }

    /**
     * Test:    throw exception
     * Given:   path url
     * Return:  throw Exception
     * When:    the class and class default are not in array config
     * @expectedException \FrontController\Exception\ClassNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenTheClassAndClasDefaultAreNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => []];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $reader->getClass('/');
    }

}
