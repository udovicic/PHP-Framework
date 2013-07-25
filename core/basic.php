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
    $user = new Core\User;

    if ($url == "") {
        global $routing_default;

        $controller = $routing_default['controller'];
        $action = $routing_default['action'];
        $queryString = $routing_default['query'];
    } else {
        $urlArray = array();
        $urlArray = explode("/", $url);

        $controller = $urlArray[0];
        array_shift($urlArray);
        $action = $urlArray[0];
        array_shift($urlArray);
        $queryString = $urlArray;
    }

    $controllerName = ucwords($controller) . 'Controller';
    $dispatch = new $controllerName($controller, $action);

    if ($dispatch->requireUser == true && $user->isLoggedIn() == false) {
        // deny access for requested action
        global $routing_user;

        // disable rendering of requested page
        $dispatch->renderPage = false;

        $controller = $routing_user['controller'];
        $action = $routing_user['action'];
        $queryString = $routing_user['query'];

        $controllerName = ucwords($controller) . 'Controller';
        $dispatch = new $controllerName($controller, $action);
    }

    if ((int)method_exists($controllerName, $action)) {
        call_user_func_array(array($dispatch, $action), $queryString);
    } else {
        throw new Exception('Action ' . $action . ' doesn\'t exists in ' . $controllerName);
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
    if (file_exists(ROOT . DS . 'core' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'core' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else {
        throw new Exception('No source for ' . $className . ' found');
    }
}

$inflect = new Inflection;

setReporting();
callHook();
