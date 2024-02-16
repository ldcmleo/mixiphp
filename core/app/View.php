<?php
namespace core\app;

/**
 * 
 * Class View
 * By: Leonardo Castro
 * 
 * Manage views
 */
class View {
    /**
     * use
     * 
     * retrieve @view to be used.
     * replaces / with . in the @view name e.j. views/dashboard/login => dashboard.login
     * 
     * @param string $view name of view in views folders
     * @param array  $data data to be showed in the view
     */
    public static function use($view, $data = NULL) {
        if(!$view) {
            throw new \Exception("View::use expected at least 1 argument but it deosn't be passed");
        }

        str_replace(".", "/", $view);
        if(!file_exists(VIEWS.$view.".php")) {
            throw new \Exception("View $view doesn't exist");
        }

        require_once VIEWS.$view.".php";
    }
}
