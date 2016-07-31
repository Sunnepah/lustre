<?php

/**
 * Created by: Sunday Ayandokun
 * Email: sunday.ayandokun@gmail.com
 * Date: 7/31/16
 * Time: 11:15 AM
 */
class ResponseTest extends \TestCase
{
    private $response;
    
    public function test_response_properties_correct() {
        
        $this->response = new \Lustre\Response("Content", 200);
        
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals("Content", $this->response->body);
    }
}
