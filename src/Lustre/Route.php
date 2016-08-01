<?php
/**
 * Created by: Sunday Ayandokun
 * Email: sunday.ayandokun@gmail.com
 * Date: 8/1/16
 * Time: 7:53 PM
 */

namespace Lustre;

use Application\Controllers;
use RuntimeException;

trait Route
{
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