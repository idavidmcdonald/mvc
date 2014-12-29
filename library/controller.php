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

    public $defaults = array();
 
    /**
     * Set properties and instantiate model and template
     * @param string $model      Name of the model
     * @param string $controller Name of the controller
     * @param string $action     Action to be done
     */
    function __construct($model, $controller, $action) {
         
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;

        $this->$model = new $model;
    }

    function addView($controller, $action){
        $this->_template = new Template($controller, $action);
        $this->setDefaults();
    }

    function addModel($model){
        $this->$model = new $model;
    }
 
    /**
     * Set variables for $this->template to populate when rendering 
     * @param string $name  name of variable
     * @param string $value value of variable
     */
    function set($name,$value) {
        if (isset($this->_template)) {
            $this->_template->set($name, $value);
        }
    }

    /**
     * Set all default variables for $this->_template as defined in $this->defaults
     */
    function setDefaults(){
        if (isset($this->_template)) {
            foreach ($this->defaults as $name => $value) {
                $this->set($name, $value);
            }
        }
    }
 
    /**
     * On destruct, render $this->_template 
     */
    function __destruct() {
        if (isset($this->_template)) {
            $this->_template->render();
        }
        
    }
    
}