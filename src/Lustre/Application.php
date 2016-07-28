<?php

/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/12/16
 * Time: 1:45 AM
 */
namespace Lustre;

use Exception;

/**
 * Class Application
 *
 * This class is the core of the Application registry
 *
 * @package Palun
 */
class Application
{
    use Router;
    
    private static $applicationInstance;

    /**
     * Application constructor.
     */
    public function __construct () { }

    /**
     * Ensure single instance of the application is created
     * @return mixed
     */
    public static function singleton () {
        if (!isset(self::$applicationInstance)) {
            $appClass = __CLASS__;
            self::$applicationInstance = new $appClass;
        }

        return self::$applicationInstance;
    }
    
    /**
     * It gets route information - Http Verb and Request path
     * If route found, it process request
     */
    public function dispatch() {

        $request = new Request();
        list($method, $pathInfo) = $this->getRequestInfo($request);
        
        try {
            if (isset($this->routes[$method . $pathInfo])) {
                return $this->handleRequest($this->routes[$method . $pathInfo]['action']);
            }

            $this->routeNotFound($method, $pathInfo);

        } catch (Exception $e) {
            return 'Caught exception: '.  $e->getMessage(). "\n";
        }
    }

    /**
     * Run the Application by calling the dispatcher
     */
    public function run() {
        
        echo $this->dispatch();
    }
}