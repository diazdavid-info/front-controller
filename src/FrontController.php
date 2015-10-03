<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 11:26
 */

namespace FrontController\FrontController;

use FrontController\Reader\ReaderConfiguration;

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
        $class = $this->_readerConfiguration->getClass($pathUrl);
        $method = $this->_readerConfiguration->getMethod($pathUrl);
        $controller = new $class();
        return $controller->$method();
    }
}