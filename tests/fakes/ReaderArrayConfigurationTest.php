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

}
