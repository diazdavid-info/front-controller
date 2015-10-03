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
     * @param $pathUrl
     * @return mixed
     */
    public function getClass($pathUrl);
}