<?php
namespace core\app;

/**
 * 
 * Class Controller
 * By: Leonardo Castro
 * 
 * this class is for load all controllers from:
 * ROOT/app/controllers/
 */
class Controller {
    /**
     * 
     * use
     * this method is used to load file in execution time
     * 
     * @param string $controller file's name for controller to be loaded an called
     * @return Controller instance of controller called or exeption con failure
     */
    public static function use(String $controller) {
        if(!$controller) {
            throw new \Exception("Controller::use expected 1 argument but it doesn't passed");
        }

        $controller = ucfirst($controller);
        if(!file_exists(CONTROLLERS.$controller.".php")) {
            throw new \Exception("Controller: $controller, doesn't exist");
        }

        require_once CONTROLLERS.$controller.".php";
        return new $controller();
    }
}
