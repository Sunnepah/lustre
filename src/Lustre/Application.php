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
 * @package Lustre
 */
class Application
{
    
    private static $applicationInstance;
    private $route;
    private $request;
    private $router;
    private $dispatcher;

    /**
     * Application constructor.
     */
    private function __construct () {
        $this->route = new Route();
        $this->request = new Request();
        $this->router = new Router();
        $this->dispatcher = new Dispatcher();
    }

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
    
    public function route() {
        return $this->route;
    }

    public function router() {
        return $this->router;
    }
    
    /**
     * It gets route information - Http Verb and Request path
     * If route found, it dispatches the request
     */
    public function dispatch() {
        
        list($method, $pathInfo) = $this->router->getRequestInfo($this->request);
        
        try {
            if (isset($this->router->routes[$method . $pathInfo])) {
                return $this->dispatcher->dispatch($this->router->routes[$method . $pathInfo]['action']);
            }

            $this->router->routeNotFound($method, $pathInfo);

        } catch (Exception $e) {
            throw new Exception('Caught exception: '.  $e->getMessage(). "\n");
        }
    }


    /**
     * Prevent instantiating class
     */
    private function __clone() {}

    /**
     * Run the Application by calling the dispatcher
     */
    public function run() {
        echo $this->dispatch();
    }
}