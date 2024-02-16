<?php
namespace core\app;
/**
 * 
 * Class View
 * By: Leonardo Castro
 * 
 * Load views from views folders
 */
class View {
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
