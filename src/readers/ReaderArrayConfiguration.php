<?php

/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 27/9/15
 * Time: 10:41
 */

namespace FrontController\Reader;


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
     * ReaderArrayConfiguration constructor.
     * @param array $_configurations
     */
    public function __construct(array $_configurations)
    {
        $this->_configurations = $_configurations;
        $this->_defaultMethod = $this->readDefaultMethod();
    }

    /**
     * @return null|string
     */
    private function readDefaultMethod()
    {
        $defaultMethod = explode('@', $this->_configurations['default']);
        return (empty($defaultMethod[0])) ? null : $defaultMethod[0];
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
        $methodAndClass = explode('@', $this->_configurations['router'][$pathUrl]);
        return $methodAndClass[0];
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


}