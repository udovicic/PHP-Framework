<?php

namespace Core;
/**
 * User management class
 *
 * Manages handling of user information through session
 */
class User
{
/** @var bool False if user is not loged in */
	private $_loggedIn;

/** @var array Stores custom fields */
	private $_variables;


/**
 * Constructor function for User class
 *
 * Handles loading data from session
 */
	function __construct()
	{
		if (isset($_SESSION['_user']['logged_in'])  == false) {
			$_SESSION['_user']['logged_in'] = false;
		}
	}

/**
 * Mark user as logged in or out
 *
 * @param bool $status true if user is logged in
 */
	function setLoggedIn($status)
	{
		$_SESSION['_user']['logged_in'] = $status;
		$_SESSION['_user']['variables'] = array();
	}

/**
 * User login status
 *
 * @return bool False if user is not logged in
 */
	function isLoggedIn()
	{
		return $_SESSION['_user']['logged_in'];
	}
/**
 * Handles custom variable storage
 *
 * @param string $name Variable name
 * @param mixed $value Value of given variable
 */
	function set($name, $value)
	{
		$_SESSION['_user']['variables'][$name] = $value;
	}

/**
 * Handles retreving values from custom variables
 *
 * @param strin $name Variable name
 * @return mixed Value of given variable, false if variable was not previously defined
 */
	function get($name)
	{
		if (array_key_exists($name, $_SESSION['_user']['variables']) == true) {
			$value = $_SESSION['_user']['variables'][$name];
		} else {
			$value = false;
		}

		return $value;
	}
}