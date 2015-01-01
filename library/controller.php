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

    function addView($controller, $action, $renderHeaderFooter = true){
        $this->_template = new Template($controller, $action, $renderHeaderFooter);
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
     * Redirect to a controller/action/queryString
     */
    function redirect($controller, $action, $parameter1 = null, $parameter2 = null, $parameter3 = null){
        // Do not add null parameters to our url
            $input = func_get_args();
            foreach ($input as $value) {
                if (!is_null($value)) {
                    $notNullInputs[] = $value;
                }
            }

        // Implode url and redirect
            $url = implode('/', $notNullInputs);
            $location = BASE_PATH . "/" . $url;
            header("Location: " . $location);
            exit;
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