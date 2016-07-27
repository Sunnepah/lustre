<?php

/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/9/16
 * Time: 5:52 PM
 */

class TestCase extends PHPUnit_Framework_TestCase
{
    
    public function createApplication()
    {
        return require_once (__DIR__ . "/../vendor/autoload.php");
    }
    
    public function test_default() {
    
    }
}
