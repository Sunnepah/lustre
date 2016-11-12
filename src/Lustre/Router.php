<?php
/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/22/16
 * Time: 2:42 PM
 */
namespace Lustre;

use RuntimeException;

class Router
{
    public $routes = [];
    
    /**
     * Register GET route
     * 
     * @param $path
     * @param $controllerAction
     */
    public function get($path, $controllerAction) {
        $this->registerRoute('GET', $path, $controllerAction);
    }

    /**
     * Register POST route
     *
     * @param $path
     * @param $controllerAction
     */
    public function post($path, $controllerAction) {
        $this->registerRoute('POST', $path, $controllerAction);
    }

    /**
     * Register PUT route
     *
     * @param $path
     * @param $controllerAction
     */
    public function put($path, $controllerAction) {
        $this->registerRoute('PUT', $path, $controllerAction);
    }

    /**
     * Register DELETE route
     *
     * @param $path
     * @param $controllerAction
     */
    public function delete($path, $controllerAction) {
        $this->registerRoute('DELETE', $path, $controllerAction);
    }

    /**
     * Register routes
     * 
     * @param $httpVerb
     * @param $path
     * @param $action
     */
    public function registerRoute($httpVerb, $path, $action) {

        $this->routes[$httpVerb . $path] = ['uri' => $path, 'method' => $httpVerb, 'action' => $action];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getRequestInfo(Request $request) {
        return [$request->getHttpMethod(), $request->path];
    }

    /**
     * @param $method
     * @param $pathInfo
     * @throws RuntimeException
     */
    public function routeNotFound($method, $pathInfo)
    {
        throw new RuntimeException("Route not found - " . $method . " on uri " . $pathInfo . " not found");
    }
}