<?php
/**
 * Templating engine
 *
 * Very basic templating engine
 *
 */
class Template
{
/** @var array Stores variables used on template */
    protected $_variables = array();

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
    function _construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

/**
 * Stores variables used on template
 *
 * @param string $name Variables name
 * @param mixed $value Value of given variable
 */
    function set($name, $value)
    {
        $this->_variables[$name] = $value;
    }

/**
 * Renders template to user
 *
 * @param bool $noHeader If true, header and footer won't be rendered (i.e. for Ajax calls)
 */
    function render($noHeader = false)
    {
        extract($this->_variables);

        // header
        if ($noHeader) {
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
            } else {
                include(ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
            }
        }

        // body
        include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');

        // fotter
        if ($noHeader) {
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
            } else {
                include(ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
            }
        }
    }
}