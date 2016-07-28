<?php

/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/13/16
 * Time: 10:23 PM
 */
class AddressesControllerTest extends TestCase
{

    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://0.0.0.0:8080'
        ]);
    }

    public function test_get_all_address_list_endpoint_returns_200() {
        $response = $this->client->get('/addresses');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertNotNull($data);
    }

    public function test_delete_request() {

        $response = $this->client->delete('/address?id=13', ['http_errors' => false]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_post_request() {

        $response = $this->client->post('/address', [
            'json' => [
                "names" => "Sunday",
                "number" => "587423953",
                "street" => "Tallinn 12345"
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertNotEmpty($data);
    }

    public function test_put_request() {

        $response = $this->client->put('/address?id=1', [
            'json' => [
                "names" => "Sunday",
                "number" => "587423953",
                "street" => "Tallinn Tartu"
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertNotEmpty($data);
    }

    public function test_put_request_fail_when_no_id() {

        $response = $this->client->put('/address', ['http_errors' => false,
            'json' => [
                "names" => "Sunday",
                "number" => "587423953",
                "street" => "Tallinn Tartu"
            ]
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
