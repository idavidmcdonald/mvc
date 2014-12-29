<?php
class Template {
    
    protected $variables = array();
    protected $_controller;
    protected $_action;
    protected $_renderHeaderFooter;
    

    /**
     * Set properties
     * @param string $controller 
     * @param string $action     
     */
    function __construct($controller, $action, $renderHeaderFooter = true) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_renderHeaderFooter = $renderHeaderFooter;
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
            if ($this->_renderHeaderFooter) {
                if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
                } else {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
                }
            }
            
 
        // Render controller/action file
            include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');       
             
        // Render appropriate footer.php file
            if ($this->_renderHeaderFooter) {     
                if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
                } else {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
                }
            }
    }
 
}