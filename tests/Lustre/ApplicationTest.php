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

    public function test_Root_Endpoint_Returns_200() {
        $response = $this->client->get('/');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('Palun', $data);
        $this->assertEquals("v1.0", $data['Palun']);
    }
}
