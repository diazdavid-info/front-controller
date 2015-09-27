<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 11:26
 */

namespace FrontController\FrontController;

use Fake\FakeController;

class FrontController
{
    /**
     * @var array
     */
    private $_arrayConfiguration = [];

    /**
     * FrontController constructor.
     * @param array $_arrayConfiguration
     */
    public function __construct(array $_arrayConfiguration)
    {
        $this->_arrayConfiguration = $_arrayConfiguration;
    }


    public function init($pathUrl)
    {
        $methodAndClass = explode('@', $this->_arrayConfiguration['router'][$pathUrl]);
        if(empty($methodAndClass[1])) {
            $methodAndClass = explode('@', $this->_arrayConfiguration['default']);
            $controller = new $methodAndClass[1]();
            return $controller->$methodAndClass[0]();
        }
        $controller = new $methodAndClass[1]();
        return $controller->$methodAndClass[0]();
    }
}