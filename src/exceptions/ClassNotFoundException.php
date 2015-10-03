<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 1/10/15
 * Time: 20:39
 */

namespace FrontController\Exception;


use Exception;

class ClassNotFoundException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}