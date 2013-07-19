<?php
/**
 * Abstract controller
 *
 * Abstraction class for controllers used in application
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

/** @var bool True if controller requires user to be logged in **/
	public $requireUser;
	
/** @var bool If true, page will be displayed */
    public $renderPage;

/** @var bool Render template without header and footer */
    public $renderHeader;

/**
 * Constructor function for Controller class
 *
 * Constructs new model and template class
 *
 * @param string $controller Name of controller class to be used
 * @param string $action Name of function to be executed
 */
    function __construct($controller, $action)
    {
        global $inflect;

        $this->renderPage = true;
        $this->renderHeader = true;
    	
		$this->requireUser = false;
		    
        $this->_controller = ucfirst($controller);
        $this->_action = $action;
        
        $model = ucfirst($inflect->singularize($controller));
        $this->$model = new $model;

        $this->_template = new Template($controller, $action);
    }

/**
 * Passes variables on to template class
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
        if ($this->renderPage) {
            $this->_template->render($this->renderHeader);
        }
    }
}
