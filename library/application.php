<?php
 
class Application {

    protected $controller;
    protected $action;
    protected $parameter1;
    protected $parameter2;
    protected $parameter3;


    function __construct(){
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->executeRequest();
    }

    /**
     * Set error and logging settings based on if or if not in development environment
     */
    public function setReporting() {
        if (DEVELOPMENT_ENVIRONMENT == true) {
            // Display all errors
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            // Dont display errors, log in tmp/logs/error.log
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
        }
    }
          
    /**
     * Strip slashes from an array or string
     * @param  mixed $value array or string to be stripped
     * @return mixed        array or string after stripping
     */
    public function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : $this->stripslashes($value);
        return $value;
    }
     

    /**
     * Check for magic quotes and remove them
     */
    public function removeMagicQuotes() {
        if ( get_magic_quotes_gpc() ) {
            $_GET    = $this->stripSlashesDeep($_GET);
            $_POST   = $this->stripSlashesDeep($_POST);
            $_COOKIE = $this->stripSlashesDeep($_COOKIE);
        }
    }
     

    /**
     * Check register globals and remove them
     */
    public function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }


    /**
     * Get the current url and load into controller, action and parameter properties
     * By default, use null if property is not set
     * Example url: /controller/action/parameter1/parameter2/parameter3
     */
    public function splitURL(){
        global $url;

        $url = explode('/', $url);
        $this->controller = (!empty($url[0]) ? $url[0] : null);
        $this->action     = (!empty($url[1]) ? $url[1] : null);
        $this->parameter1 = (isset($url[2]) ? $url[2] : null);
        $this->parameter2 = (isset($url[3]) ? $url[3] : null);
        $this->parameter3 = (isset($url[4]) ? $url[4] : null);
    }


    /**
     * Validate $this->controller and $this->action
     * If they are not set then set as default values
     * If they are invalid then set as error page values
     */
    public function validateRequest(){
        global $default;

        // Validate controller
            if (!($this->controller)) {
                // No controller set, Use defaults
                    $this->controller = $default['controller'];
                    $this->action     = $default['action'];
                    $this->parameter1 = $default['parameter1']; 
                    $this->parameter2 = $default['parameter2'];
                    $this->parameter3 = $default['parameter3'];
            } 

            // Check controller exists
            
        // Validate action
            if (!isset($this->action)) {
                // No action set, try index
                    $this->action = 'index';
            } 

            // Create controller to dispatch our request, eg new ItemsController
                $model = ucwords(rtrim($this->controller, 's'));
                $controller = ucwords($this->controller) . 'Controller';
                $dispatch = new $controller($model, $this->controller, $this->action);

            // Check $action method exists
                if (!method_exists($dispatch, $this->action)) {
                    // Show error page
                }                      
    }

    /**
     * Get the URL that has been requested, validate it and then
     * create the controller and dispatch the action through that controller
     */
    public function executeRequest() {
        $this->splitURL();
        $this->validateRequest();

        // Create controller to dispatch our request, eg new BlogsController
        // Note format eg $dispatch = new BlogsController('Blog', 'blogs', 'index')
            $model = ucwords(rtrim($this->controller, 's'));
            $controller = ucwords($this->controller) . 'Controller';
            $dispatch = new $controller($model, $this->controller, $this->action);

        // Execute
            if (!isset($this->parameter1)) {
                call_user_func(array($dispatch, $this->action));
            } else if (!isset($this->parameter2)) {
                call_user_func(array($dispatch, $this->action), $this->parameter1);
            } else if (!isset($this->parameter3)) {
                call_user_func(array($dispatch, $this->action), $this->parameter1, $this->parameter2);
            } else {
                call_user_func(array($dispatch, $this->action), $this->parameter1, $this->parameter2, $this->parameter3);
            }
    }
 
}
