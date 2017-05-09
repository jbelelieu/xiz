<?php

/**
 * XIZ PHP Framework
 * 
 * A lightweight MVC-based framework for rapid development of
 * web-based applications.
 *
 * @author      Jon Belelieu (@jbelelieu)
 * @link        http://twitter.com/jbelelieu
 * @link        https://www.castlamp.com/
 * @license     GPLv3
 */

// Load the app and environment.
require dirname(dirname(__FILE__)) . "/config/load.php";

// Route the request.
require dirname(dirname(__FILE__)) . "/config/routes.php";

$route = $dispatcher->dispatch($request->getMethod(), $request->getPathRaw());

switch ($route[0]) {

    // Endpoint does not exist.
    case FastRoute\Dispatcher::NOT_FOUND:
        echo (new Lib\Reply)->setCode('901')->send();
        break;

    // Found the endpoint: run the command
    case FastRoute\Dispatcher::FOUND:
        list($controller, $method) = explode(':',$route['1']);

        $vars = $route[2];

        try {
            $class = "Controller\\" . $controller;
            $loadedController = new $class($app, $request);
            call_user_func_array(array($loadedController, $method), $vars);
        } catch (Exception $e) {
            echo (new Lib\Reply)->setCode('901')->setData($e->getMessage())->send();
        }

        break;
}

