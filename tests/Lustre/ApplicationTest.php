<?php

/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/12/16
 * Time: 2:19 AM
 */

use Lustre\Application;

/**
 * Class ApplicationTest.
 */ 
class ApplicationTest extends TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://0.0.0.0:8080'
        ]);
    }

    public function testApplicationInstanceCreated() {
        $this->assertInstanceOf(Application::class, Application::singleton());
    }
}
