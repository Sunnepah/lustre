<?php
/**
 * Created by: Sunday Ayandokun
 * Email: sunday.ayandokun@gmail.com
 * Date: 8/13/16
 * Time: 7:19 PM
 */

namespace Lustre;

use RuntimeException;

class Dispatcher
{
    /**
     * Resolves route action to Callable Controller class and method
     * @param $controllerAction
     * @return mixed
     */
    public function dispatch($controllerAction)
    {
        list($controller, $action) = explode(":", $controllerAction);

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