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
		$this->_variables = array();

		if (isset($_SESSION['_user']['logged_in'])  == true) {
			$this->_loggedIn = $_SESSION['_user']['logged_in'];
		} else {
			$this->_loggedIn = false;
		}

		if ($this->_loggedIn == true) {
			$this->_variables = $_SESSION['_user']['variables'];
		}
	}

/**
 * Mark user as logged in or out
 *
 * @param bool $status true if user is logged in
 */
	function setLogedIn($status)
	{
		if ($status == false) {
			$this->_loggedIn = false;
			$this->_variables = array();

			$_SESSION['_user']['logged_in'] = false;
			$_SESSION['_user']['variables'] = array();
		} else {
			$this->_loggedIn = $status;

			$_SESSION['_user']['logged_in'] = $status;
		}

	}

/**
 * User login status
 *
 * @return bool False if user is not logged in
 */
	function isLoggedIn()
	{
		if ($this->_loggedIn == false) {
			return false;
		} else {
			return $this->_loggedIn;
		}
	}
/**
 * Handles custom variable storage
 *
 * @param string $name Variable name
 * @param mixed $value Value of given variable
 */
	function set($name, $value)
	{
		$this->_variables[$name] = $value;
	}

/**
 * Handles retreving values from custom variables
 *
 * @param strin $name Variable name
 * @return mixed Value of given variable, false if variable was not previously defined
 */
	function get($name)
	{
		if (array_key_exists($name, $this->_variables) == true) {
			$value = $this->_variables[$name];
		} else {
			$value = false;
		}

		return $value;
	}
}