<?php


/**
 * @param $date
 *
 * @return bool|string
 */
function format_date($date)
{
    return date('m/d/Y g:ia', strtotime($date));
}


/**
 * @param bool $time
 *
 * @return bool|string
 */
function current_date($time = true)
{
    if ($time) {
        return date('Y-m-d H:i:s');
    } else {
        return date('Y-m-d');
    }
}


/**
 * @param $data
 * @param string $title
 */
function pa($data, $title = '')
{
    echo "<hr><hr><hr>";
    if (! empty($title)) {
        echo "<h1>" . $title . "</h1>";
    }
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}


/**
 * Shortcut to get config item.
 *
 * @param $key
 *
 * @return mixed
 */
function config($key)
{
    global $app;

    return $app->config($key);
}


/**
 * @param $key
 *
 * @return mixed
 */
function lang($key)
{
    global $app;

    return $app->lang($key);
}
