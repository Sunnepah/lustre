<?php
/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/22/16
 * Time: 2:42 PM
 */
namespace Lustre;

use Application\Controllers;
use RuntimeException;

trait Router
{
    protected $routes = [];
    
    /**
     * Register GET route
     * 
     * @param $path
     * @param $controllerAction
     */
    public function get($path, $controllerAction) {
        $this->registerRoute ('GET', $path, $controllerAction);
    }

    /**
     * Register POST route
     *
     * @param $path
     * @param $controllerAction
     */
    public function post($path, $controllerAction) {
        $this->registerRoute ('POST', $path, $controllerAction);
    }

    /**
     * Register PUT route
     *
     * @param $path
     * @param $controllerAction
     */
    public function put($path, $controllerAction) {
        $this->registerRoute ('PUT', $path, $controllerAction);
    }

    /**
     * Register DELETE route
     *
     * @param $path
     * @param $controllerAction
     */
    public function delete($path, $controllerAction) {
        $this->registerRoute ('DELETE', $path, $controllerAction);
    }

    /**
     * Register routes
     * 
     * @param $httpVerb
     * @param $path
     * @param $action
     */
    public function registerRoute ($httpVerb, $path, $action) {

        $this->routes[$httpVerb . $path] = ['uri' => $path, 'method' => $httpVerb, 'action' => $action];
    }

    /**
     * @return array
     */
    protected function getRequestInfo(Request $request) {
        return [$request->getHttpMethod(), $request->path];
    }

    /**
     * @param $method
     * @param $pathInfo
     * @throws RuntimeException
     */
    protected function routeNotFound($method, $pathInfo)
    {
        throw new RuntimeException("Route not found - " . $method . " on uri " . $pathInfo . " not found");
    }

    /**
     * Resolves route action to Callable Controller class and method
     * @param $ControllerAction
     * @return mixed
     */
    public function handleRequest($ControllerAction) {
        
        list($controller, $action) = explode(":", $ControllerAction);
        
        $response = $this->callControllerAction($controller, $action);
        
        return $response;
    }

    /**
     * It instantiate Controller class and invoke method call 
     * @param $controller
     * @param $action
     * @return mixed
     */
    protected function callControllerAction($controller, $action)
    {
        $namespace = "\\Application\\Controllers\\";
        
        if (!method_exists($instance = $namespace.$controller, $action)) {
            throw new RuntimeException("Method " . $action . " does not exist!");
        }
        
        $controller = new $instance();
        
        return $controller->$action();
    }
}