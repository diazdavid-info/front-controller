<?php
/**
 * Created by PhpStorm.
 * User: daviddiaz
 * Date: 26/9/15
 * Time: 11:20
 */

namespace frontController\test\fakes;


class FakeController
{

    public function add()
    {
        return "Hello from method add class FakeController";
    }

    public function index()
    {
        return "Default";
    }

    public function param($id)
    {
        return "param: " . $id;
    }

    public function params($id, $idSchool)
    {
        return "params: " . $id . ", " . $idSchool;
    }

}