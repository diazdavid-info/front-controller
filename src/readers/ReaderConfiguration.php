<?php
/**
 * Interface to Readers configuration
 * User: daviddiaz
 * Date: 27/9/15
 * Time: 10:41
 */

namespace FrontController\Reader;


interface ReaderConfiguration
{
    /**
     * @param string $pathUrl
     * @return string
     */
    public function getMethod($pathUrl);

    /**
     * @param string $pathUrl
     * @return string
     */
    public function getClass($pathUrl);

    /**
     * @param string $pathUrl
     * @return array
     */
    public function getParameters($pathUrl);
}