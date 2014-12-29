<?php

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