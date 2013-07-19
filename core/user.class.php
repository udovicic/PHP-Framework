<?php

/**
 * User management class
 *
 * Manages handling of user information through session
 */
class User
{
/** @var bool True if user is loged in */
	public $isLogedIn;

/** @var array Stores custom fields */
	private $_variables;


/**
 * Constructor function for User class
 *
 * Handles loading data from session
 */
	function __construct()
	{
		if (isset($_SESSION['_user']['logged_in'])  == true) {
			$this->isLogedIn = $_SESSION['_user']['logged_in'];
		} else {
			$this->isLogedIn = false;
		}

		if ($this->isLogedIn == true) {
			$this->_variables = $_SESSION['_user']['variables'];
		}
	}

/**
 * Destructor function for User class
 *
 * Handles data storage into session
 */
	function __destruct()
	{
		$_SESSION['_user']['logged_in'] = $this->isLogedIn;

		if ($this->isLogedIn == true) {
			$_SESSION['_user']['variables'] = $this->_variables;
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
		if (array_key_exists($name) == true) {
			$value = $this->_variables[$name];
		} else {
			$value = false;
		}

		return $value;
	}
}