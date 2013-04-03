<?php
/**
 * Basic functionality
 *
 * Core functions for including other source files, loading additional classes and so forth
 */

/**
 * Error reporting method
 *
 * Manages the way in which errors will be reported
 */
function setReporting()
{
    global $config;
    if (DEVELOPMENT == true) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . DS . 'error.log');
    }
}

/**
 * Main call function
 *
 * Analyzes url requests and calls requested controller function
 */
function callHook()
{
    global $url; // defined in public/index.php

    if ($url == "") {
        global $default;

        $controller = $default['controller'];
        $action = $default['action'];
    } else {
        $urlArray = array();
        $urlArray = explode("/", $url);

        $controller = $urlArray[0];
        array_shift($urlArray);
        $action = $urlArray[0];
        array_shift($urlArray);
        $queryString = $urlArray;
    }

    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller, 's');
    $controller .= 'Controller';
    $dispatch = new $controller($model, $controllerName, $action);

    if ((int)method_exists($controller, $action)) {
        call_user_func_array(array($dispatch, $action), queryString);
    } else {
        // Error Generation Code
    }
}

/**
 * Autoload classes
 *
 * Searches for the source file of requested class
 *
 * @param string $className Name of class to be loaded
 */
function __autoload($className)
{
    if (file_exists(ROOT . DS . 'core' . DS . strtolower($className) . DS . '.class.php')) {
        require_once(ROOT . DS . 'core' . DS . strtolower($className) . DS . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else {
        // Error Generation Code
    }
}

setReporting();
callHook();
