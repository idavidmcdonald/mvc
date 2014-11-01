<?php
class Template {
    
    protected $variables = array();
    protected $_controller;
    protected $_action;
    
    
    /**
     * [__construct description]
     * @param [type] $controller [description]
     * @param [type] $action     [description]
     */
    function __construct($controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
    }
 
   
    /**
     * Add a variable to be set when our template is rendered
     * @param string $name  name of the variable
     * @param string $value value for the variable
     */
    function set($name, $value) {
        $this->variables[$name] = $value;
    }
 
    
    /**
     * Render the page with a header, main content and footer view
     */
    function render() {
        // Extract variables to be rendered in templates
            extract($this->variables);
         
        // Render appropriate header.php file
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
            } else {
                include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
            }
 
        // Render controller/action file
            include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');       
             
        // Render appropriate footer.php file     
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
            } else {
                include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
            }
    }
 
}