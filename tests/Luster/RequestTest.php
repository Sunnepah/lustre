<?php
/**
 * Created by: Sunday Ayandokun
 * Email: sunday.ayandokun@gmail.com
 * Date: 7/2/16
 * Time: 5:11 PM
 */

use Luster\Request;

class RequestTest extends \TestCase
{
    private $request;
    
    function setUp () {
        /** Set HTTP request properties */
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }
    
    public function test_request_has_properties() {
        $_SERVER['REQUEST_URI'] = '/';
        
        $this->request = new Request();
        
        $this->assertEquals("/", $this->request->url);
        $this->assertEquals("GET", $this->request->method);
    }

    public function test_get_request_parse_query_properties() {
        $_SERVER['REQUEST_URI'] = '/address?id=1';

        $this->request = new Request();
        
        $this->assertEquals("/address?id=1", $this->request->url);
        $this->assertEquals("/address", $this->request->path);
        $this->assertEquals("GET", $this->request->method);
    }

    public function test_post_request_body_parsed() {
        $_SERVER['REQUEST_URI'] = '/address';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $_POST = ["names" => "Michal","number" => "506088156",  "street" => "Michalowskiego 41"];

        $this->request = new Request();

        $this->assertEquals("/address", $this->request->url);
        $this->assertEquals("POST", $this->request->method);
        
        $this->assertNotEmpty($this->request->data);
    }
}
