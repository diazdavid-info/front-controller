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
     * Test:    Call index of FakeController
     * Given:   Path URL
     * Return:  Hello
     * When:    Always
     */
    public function testCallIndexOfFakeControllerGivenPathUrlReturnHelloWhenAlways()
    {
        $frontController = new FrontController();
        $result = $frontController->init('/');
        $this->assertTrue($result === 'Hello');
    }


}
