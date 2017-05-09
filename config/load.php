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

define('BOOTSTRAP_START_TIME', microtime(true));

// Autoloader
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

// What environment are we using?
$env = file_get_contents(dirname(dirname(__FILE__)) . '/ENV');

$configFile = dirname(__FILE__) . '/' . $env . '/config.php';

if (file_exists($configFile)) {

    $config = require dirname(__FILE__) . '/' . $env . '/config.php';

    if ($config['debug']) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    } else {
        ini_set('display_errors', 0);
    }

    $app = (new Lib\App())->setConfig($config);

    $request = new Lib\Request();


    // Input handling
    $input = [];

    switch (strtolower($_SERVER['REQUEST_METHOD'])) {
        case 'post':
            $input = $_POST;
            break;
        case 'get':
            $input = $_GET;
            break;
        default:
            $input = json_decode(file_get_contents("php://input"), true);
    }

    if (empty($input)) {
        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input)) {
            parse_str(file_get_contents("php://input"), $input);
        }
    }

    $request->setInput((array)$input);

    require dirname(dirname(__FILE__)) . '/lib/universal.php';

} else {
    echo 'Invalid environment.';
    exit;
}