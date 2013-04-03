<?php
/**
 * Abstract controller
 *
 * Abstraction class for controllers used in aplication
 */
abstract class Controller
{
/** @var string Stores attached model name */
    protected $_model;

/** @var string Stores controller name */
    protected $_controller;

/** @var string Stores called action */
    protected $_action;

/** @var object Points to template engine class */
    protected $_template;

/**
 * Constructior function for Controller class
 *
 * Constrcts new model and template class
 *
 * @param string $model Name of model class to be used
 * @param string $controller Name of controller class to be used
 * @param string $action Name of function which is to be executed
 */
    function __construct($model, $controller, $action)
    {
        $this->_model = $model;
        $this->_controller = $controller;
        $this->_action = $action;

        $this->$model = new $model;
        $this->_template = new Template($controller, $action);
    }

/**
 * Passes variables to template class
 *
 * @param string $name Name of variable in template
 * @param mixed $value Value of given variable
 */
    function set($name, $value)
    {
        $this->_template->set($name, $value);
    }

/**
 * Destructor function for Controller class
 *
 * Renders template to user
 */
    function __destruct()
    {
        $this->_template->render();
    }
}