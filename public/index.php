<?php
/**
 * Request collector
 *
 * This page is the single entry point for all requests made by the application
 */

/** directory separator string */
define('DS', DIRECTORY_SEPARATOR);
/** Root folder of the framework */
define('ROOT', dirname(dirname(__FILE__)));

$url = isset($_GET['url']) == true ? $_GET['url'] : "";

require_once(ROOT . DS . 'core' . DS . 'bootstrap.php');
