<?php
/**
 * 
 * 
 */
class Model extends Database {

    public function __construct() {
        parent::__construct();
    }

    public static function use($model) {
        if(!$model) {
            throw new Exception("Model::use expected 1 argument but it doesn't exist");
        }

        $model = ucfirst($model);
        if(!file_exists(MODELS.$model.".php")) {
            throw new Exception("Model: $model, doesn't exist");
        }

        require_once MODELS.$model.".php";
        return new $model();
    }

    public function select(array $columns) {

        return $this;
    }
}
