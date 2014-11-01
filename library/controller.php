<?php

/**
* 
*/
class Controller 
{

	protected $_model;
	protected $_controller;
    protected $_action;
    protected $_template;
 
    /**
     * [__construct description]
     * @param string $model      Name of the model
     * @param string $controller Name of the controller
     * @param string $action     Action to be done
     */
    function __construct($model, $controller, $action) {
         
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;
 
        $this->$model = new $model;
        $this->_template = new Template($controller, $action);
 
    }
 
    /**
     * Set variables for $this->template to populate when rendering 
     * @param string $name  name of variable
     * @param string $value value of variable
     */
    function set($name,$value) {
        $this->_template->set($name, $value);
    }
 
    /**
     * On destruct, render $this->_template 
     */
    function __destruct() {
            $this->_template->render();
    }
    
}