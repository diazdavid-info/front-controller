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

    public function init($pathUrl)
    {
        $fakeController = new FakeController();
        return $fakeController->index();
    }

}