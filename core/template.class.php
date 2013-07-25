<?php

namespace Core;
/**
 * Templating engine
 *
 * Very basic templating engine
 *
 */
class Template
{
/** @var array Stores variables used on template */
    protected $_variables;

/** @var string Stores attached controller name */
    protected $_controller;

/** @var string Stores called action name */
    protected $_action;

/**
 * Constructor function for Template class
 *
 * @param string $controller Attached controller name
 * @param string $action Called action
 */
    function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_variables = array();
    }

/**
 * Stores variables used on template
 *
 * @param string $name Variable name
 * @param mixed $value Value of given variable
 */
    function set($name, $value)
    {
        $this->_variables[$name] = $value;
    }

/**
 * Renders template to user
 *
 * @param bool $renderHeader If false, header and footer will be omitted in rendering process
 */
    function render($renderHeader = true)
    {
        extract($this->_variables);
        
        // header
        if ($renderHeader) {
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
            } else {
                include(ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
            }
        }

        // body
        include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');

        // fotter
        if ($renderHeader) {
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
            } else {
                include(ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
            }
        }
    }
}