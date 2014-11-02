<?php
 
/**
 * Set error and logging settings based on if or if not in development environment
 */
function setReporting() {
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
 

// Check for Magic Quotes and remove them
 
/**
 * [stripSlashesDeep description]
 * @param  [type] $value
 * @return [type]
 */
function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}
 

/**
 * [removeMagicQuotes description]
 * @return [type]
 */
function removeMagicQuotes() {
    if ( get_magic_quotes_gpc() ) {
        $_GET    = stripSlashesDeep($_GET   );
        $_POST   = stripSlashesDeep($_POST  );
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}
 

// Check register globals and remove them

/**
 * [unregisterGlobals description]
 */
function unregisterGlobals() {
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
 * Intepret an url of the format /controller/action/query, e.g. /items/viewall/
 * Create the controller and dispatch the action through that controller
 */
function callHook() {
    global $url;
    global $default;

    // Intepret url
        if (!isset($url)) {
            // No url, set default controller action and queryString
                $controller = $default['controller'];
                $action = $default['action'];
                $queryString = $default['queryString'];                
        } else {
            $urlArray = array();
            $urlArray = explode("/",$url);

            $controller = $urlArray[0];
            array_shift($urlArray);
            $action = $urlArray[0];
            array_shift($urlArray);
            $queryString = $urlArray;
        }   
 
    /*
    Set $controllerName to be the general controller name, ie 'items'
    Set $controller to be the full string for $controllerName, e.g. 'ItemsController'
    Set $model to be the model to be used, e.g. 'Item'
    Set $action to be the required action, e.g. 'viewall'
     */
    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller, 's');
    $controller .= 'Controller';
    
    // Create controller to dispatch our request, eg new ItemsController
        $dispatch = new $controller($model, $controllerName, $action);

    // If $action method exists then run $dispatch->$action
        if ((int)method_exists($dispatch, $action)) {
            call_user_func_array(array($dispatch,$action), $queryString);
        } else {
            /* Error Generation Code Here */
        }
}
 
/**
 * Autoloader for library classes, controllers and models
 */
spl_autoload_register(function ($className) {
    // Load library classes
        if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.php')) {
            require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.php');

    // Load controllers
        } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
            require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');

    // Load models
        } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
            require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
        
        } else {
            /* Error Generation Code Here */
        }
}); 


 
 
setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();