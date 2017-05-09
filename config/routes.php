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

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '', 'Home:home');

});