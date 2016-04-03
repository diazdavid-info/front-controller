<?php

/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 27/9/15
 * Time: 10:41
 */

namespace frontController\readers;

use frontController\exceptions\ClassNotFoundException;
use frontController\exceptions\MethodNotFoundException;

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
     * @var array
     */
    private $_explodeRouter;
    /**
     * @var array
     */
    private $_explodePathUrl;

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
        return empty($this->_configurations['default']) ? null : explode('@', $this->_configurations['default']);
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
        $methodAndClass = array();
        foreach ($this->_configurations['router'] as $router => $value) {
            $this->_explodeRouter = explode('/', $router);
            $this->_explodePathUrl = explode('/', $pathUrl);

            $result = $this->mergeRouterAndPath();

            if ($this->_explodeRouter == $result) {
                $methodAndClass = explode('@', $value);
            }
        }
        return empty($methodAndClass[0]) ? null : $methodAndClass[0];
    }

    /**
     * @return array
     */
    private function mergeRouterAndPath()
    {
        $result = array();

        if (count($this->_explodePathUrl) == count($this->_explodeRouter)) {
            $result = array_filter($this->_explodeRouter, function ($value, $key) {
                return ($this->_explodePathUrl[$key] === $this->_explodeRouter[$key] || $this->isParameter($this->_explodeRouter[$key]));
            }, ARRAY_FILTER_USE_BOTH);
        }
        return $result;
    }

    /**
     * @param string $parameter
     * @return int
     */
    private function isParameter($parameter)
    {
        return preg_match('/{\w+}/', $parameter);
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
     * @param $pathUrl
     * @return array
     */
    private function cutRouter($pathUrl)
    {
        return empty($this->_configurations['router'][$pathUrl]) ? null : explode('@', $this->_configurations['router'][$pathUrl]);
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

    /**
     * @param string $pathUrl
     * @return array
     */
    public function getParameters($pathUrl)
    {
        $parameters = array();
        if (!$this->existsRouter($pathUrl)) {
            $this->_explodePathUrl = explode('/', $pathUrl);
            $parameters = $this->readAllRouters();
        }
        return $parameters;
    }

    /**
     * @param $pathUrl
     * @return bool
     */
    private function existsRouter($pathUrl)
    {
        return !empty($this->_configurations['router'][$pathUrl]);
    }

    /**
     * @return array
     */
    private function readAllRouters()
    {
        $parameters = array();
        foreach ($this->_configurations['router'] as $router => $value) {
            if ($this->hasParameters($router)) {
                $this->_explodeRouter = explode('/', $router);

                $result = $this->mergeRouterAndPath();

                if ($this->_explodeRouter == $result) {
                    $valuePath = $this->getVariables();
                    $nameParameter = explode(':', $value);
                    array_shift($nameParameter);
                    $parameters = array_combine($nameParameter, $valuePath);
                }
            }
        }
        return $parameters;
    }

    /**
     * @param $router
     * @return int
     */
    private function hasParameters($router)
    {
        return preg_match_all('/{\w+}/', $router, $matches, PREG_OFFSET_CAPTURE);
    }

    /**
     * @return array
     */
    private function getVariables()
    {
        $result = array_filter($this->_explodePathUrl, function ($value, $key) {
            return ($this->isParameter($this->_explodeRouter[$key]));
        }, ARRAY_FILTER_USE_BOTH);

        return $result;
    }
}