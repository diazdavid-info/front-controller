<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 11:26
 */

namespace frontController;

use frontController\readers\ReaderConfiguration;

class FrontController
{
    /**
     * @var ReaderConfiguration
     */
    private $_readerConfiguration;

    /**
     * FrontController constructor.
     * @param ReaderConfiguration $readerConfiguration
     */
    public function __construct(ReaderConfiguration $readerConfiguration)
    {
        $this->_readerConfiguration = $readerConfiguration;
    }

    /**
     * @param string $pathUrl
     * @return string
     */
    public function init($pathUrl)
    {
        $class = new \ReflectionClass($this->_readerConfiguration->getClass($pathUrl));
        $instance = $class->newInstance();
        $method = $class->getMethod($this->_readerConfiguration->getMethod($pathUrl));
        return $method->invokeArgs($instance, $this->_readerConfiguration->getParameters($pathUrl));
    }
}