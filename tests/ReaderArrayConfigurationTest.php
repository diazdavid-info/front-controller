<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 27/9/15
 * Time: 10:36
 */

namespace Fake;


use frontController\exceptions\ClassNotFoundException;
use frontController\exceptions\MethodNotFoundException;
use frontController\readers\ReaderArrayConfiguration;
use frontController\test\fakes\FakeController;
use PHPUnit_Framework_TestCase;

class ReaderArrayConfigurationTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test:    get method
     * Given:   path url
     * Return:  name method
     * When:    the method is specified in array config
     * @throws MethodNotFoundException
     */
    public function testGetMethodGivenPathUrlReturnNameMethodWhenTheMethodIsSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' =>
            [
                '/' => 'add@frontController\test\fakes\FakeController',
                '/test/{id}' => 'test2@frontController\test\fakes\FakeController:id',
            ]
        ];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $method = $reader->getMethod('/');
        $this->assertSame('add', $method);

        $method = $reader->getMethod('/test/12');
        $this->assertSame('test2', $method);
    }

    /**
     * Test:    get method default
     * Given:   path url
     * Return:  name method default
     * When:    the method is not specified in array config
     * @throws MethodNotFoundException
     */
    public function testGetMethodDefaultGivenPathUrlReturnNameMethodDefaultWhenTheMethodIsNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@frontController\test\fakes\FakeController'], 'default' => 'index@frontController\test\fakes\FakeController'];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $methodDefault = $reader->getMethod('/add');
        $this->assertSame('index', $methodDefault);
    }

    /**
     * Test:    throw exception
     * Given:   path url
     * Return:  Throw Exception
     * When:    the method and method default are not in array config
     * @expectedException \frontController\exceptions\MethodNotFoundException
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
     * @throws ClassNotFoundException
     */
    public function testGetClassGivenPathUrlReturnNameClassWhenTheClassIsSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@frontController\test\fakes\FakeController']];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $class = $reader->getClass('/');
        $this->assertSame(FakeController::class, $class);
    }

    /**
     * Test:    get class default
     * Given:   path url
     * Return:  name class default
     * When:    the class is not specified in array config
     * @throws ClassNotFoundException
     */
    public function testGetClassDefaultGivenPathUrlReturnNameClassDefaultWhenTheClassIsNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => ['/' => 'add@frontController\test\fakes\FakeController'], 'default' => 'index@Fake\FakeDefaultController'];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $classDefault = $reader->getClass('/add');
        $this->assertSame($classDefault, 'Fake\FakeDefaultController');

        $arrayConfiguration = ['router' => ['/test/{id}' => 'test2@frontController\test\fakes\FakeController:id']];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $class = $reader->getClass('/test/12');
        $this->assertSame($class, FakeController::class);
    }

    /**
     * Test:    throw exception
     * Given:   path url
     * Return:  throw Exception
     * When:    the class and class default are not in array config
     * @expectedException \frontController\exceptions\ClassNotFoundException
     */
    public function testThrowExceptionGivenPathUrlReturnThrowExceptionWhenTheClassAndClassDefaultAreNotSpecifiedInArrayConfig()
    {
        $arrayConfiguration = ['router' => []];
        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $reader->getClass('/');
    }

    /**
     * Test:    get parameters
     * Given:   path url
     * Return:  empty array
     * When:    there are not parameters in path
     */
    public function testGetParametersGivenPathUrlReturnEmptyArrayWhenThereAreNotParametersInPath()
    {
        $arrayConfiguration = ['router' => [
            '/teacher' => 'add@frontController\test\fakes\FakeController',
            '/param/{id}' => 'add@frontController\test\fakes\FakeController:id',]];

        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $parameters = $reader->getParameters('/teacher');
        $this->assertTrue($parameters == []);
        $parameters = $reader->getParameters('/param');
        $this->assertTrue($parameters == []);
    }

    /**
     * Test:    get parameters
     * Given:   path url
     * Return:  array key value with parameters
     * When:    there are parameters in path
     */
    public function testGetParametersGivenPathUrlReturnArrayKeyValueWhenThereAreParametersInPath()
    {
        $arrayConfiguration = ['router' => [
            '/param/{id}' => 'add@frontController\test\fakes\FakeController:id',
            '/user/{idUser}' => 'add@frontController\test\fakes\FakeController:idUser',
            '/school/{idSchool}/name/{nameSchool}' => 'add@frontController\test\fakes\FakeController:idSchool:nameSchool',
            '/school/{idSchool}/class/{idClass}/module/{idModule}/{idCicle}' => 'add@frontController\test\fakes\FakeController:idSchool:idClass:idModule:idCicle']];

        $reader = new ReaderArrayConfiguration($arrayConfiguration);
        $parameters = $reader->getParameters('/param/1');
        $this->assertTrue($parameters == ['id' => '1']);
        $parameters = $reader->getParameters('/user/10');
        $this->assertTrue($parameters == ['idUser' => '10']);
        $parameters = $reader->getParameters('/school/10/name/clara-del-rey');
        $this->assertTrue($parameters == ['idSchool' => '10', 'nameSchool' => 'clara-del-rey']);
        $parameters = $reader->getParameters('/school/10/name/clara-del-rey');
        $this->assertTrue($parameters == ['idSchool' => '10', 'nameSchool' => 'clara-del-rey']);
        $parameters = $reader->getParameters('/school/10/class/i5/module/dam/2b');
        $this->assertTrue($parameters == ['idSchool' => '10', 'idClass' => 'i5', 'idModule' => 'dam', 'idCicle' => '2b']);
    }

}
