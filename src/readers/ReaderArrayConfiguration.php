<?php

/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 27/9/15
 * Time: 10:41
 */

namespace FrontController\Reader;


use FrontController\Exception\ClassNotFoundException;
use FrontController\Exception\MethodNotFoundException;

class ReaderArrayConfiguration implements ReaderConfiguration
{
    /**
     * @var array
     */
    private $_configurations;
    /**
     * @var string
     */
    private $_defaultMethod;
    /**
     * @var string
     */
    private $_defaultClass;

    /**
     * ReaderArrayConfiguration constructor.
     * @param array $_configurations
     */
    public function __construct(array $_configurations)
    {
        $this->_configurations = $_configurations;
        $this->_defaultMethod = $this->readDefaultMethod();
        $this->_defaultClass = $this->readDefaultClass();
    }

    /**
     * @return null|string
     */
    private function readDefaultMethod()
    {
        $defaultMethod = $this->cutDefaultRouter();
        return (empty($defaultMethod[0])) ? null : $defaultMethod[0];
    }

    /**
     * @return array
     */
    private function cutDefaultRouter()
    {
        return explode('@', $this->_configurations['default']);
    }

    /**
     * @return null|string
     */
    private function readDefaultClass()
    {
        $defaultClass = $this->cutDefaultRouter();
        return (empty($defaultClass[1])) ? null : $defaultClass[1];
    }

    /**
     * @param string $pathUrl
     * @return string
     * @throws MethodNotFoundException
     */
    public function getMethod($pathUrl)
    {
        $method = $this->readMethod($pathUrl);
        return (empty($method)) ? $this->getDefaultMethod() : $method;
    }

    /**
     * @param string $pathUrl
     * @return string
     */
    private function readMethod($pathUrl)
    {
        $methodAndClass = $this->cutRouter($pathUrl);
        return $methodAndClass[0];
    }

    /**
     * @param $pathUrl
     * @return array
     */
    private function cutRouter($pathUrl)
    {
        return explode('@', $this->_configurations['router'][$pathUrl]);
    }

    /**
     * @return string
     * @throws MethodNotFoundException
     */
    private function getDefaultMethod()
    {
        if (empty($this->_defaultMethod)) {
            throw new MethodNotFoundException('Method not found in array configuration');
        }
        return $this->_defaultMethod;
    }

    /**
     * @param string $pathUrl
     * @return string
     */
    public function getClass($pathUrl)
    {
        $methodAndClass = $this->cutRouter($pathUrl);
        return (empty($methodAndClass[1])) ? $this->getDefaultClass() : $methodAndClass[1];
    }

    /**
     * @return string
     * @throws ClassNotFoundException
     */
    private function getDefaultClass()
    {
        if (empty($this->_defaultClass)) {
            throw new ClassNotFoundException('Class not found in array configuration');
        }
        return $this->_defaultClass;
    }


}