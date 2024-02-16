<?php
namespace core\app;
use core\db\Database;
/**
 * 
 * 
 */
class Model {
    private $tableName;
    private $attributes;
    
    public function __construct($attributes = NULL) {
        $this->tableName = $this->setTableName();
        if($attributes) $this->attributes = $attributes;
    }

    public static function use($model) {
        if(!$model) {
            throw new \Exception("Model::use expected 1 argument but it doesn't exist");
        }

        $model = ucfirst($model);
        if(!file_exists(MODELS.$model.".php")) {
            throw new \Exception("Model: $model, doesn't exist");
        }

        require_once MODELS.$model.".php";
        return new $model();
    }

    /**
     * save
     * insert current model child class into respective table in db
     * 
     * @return boolean true on success, false on fail
     */
    public function save() {
        if(!$this->attributes) {
            throw new \Exception("Model " . get_class($this) . " need attributes to insert into table: " . $this->getTableName());
        }
        
        $columns = "";
        $values = "";

        foreach($this->attributes as $column => $value) {
            $columns .= "$column, ";
            $values .= ":$column, ";
        }
        
        $columns = substr($columns, 0, -2);
        $values = substr($values, 0, -2);
        
        $sql = "INSERT INTO `$this->tableName` ($columns) VALUES ($values)";

        $db = new Database;
        $db->query($sql);
        foreach($this->attributes as $column => $value) {
            $db->bind(":$column", $value);
        }

        return $db->exec();
    }

    public function get() { }
    public function build() { }

    /**
     * Getters and Setters
     */

    /**
     * setTableName
     */
    public function setTableName() {
        $modelName = (new \ReflectionClass($this))->getShortName();
        $modelNameAsArray = preg_split('/\B(?=[A-Z])/s', $modelName);
        $tableName = "";
        foreach ($modelNameAsArray as $word) {
            $tableName .= strtolower($word) . "_";
        }

        return substr($tableName, 0 , -1);
    }

    /**
     * getTableName
     * 
     * @return string name of the represented table in the database
     */
    public function getTableName() { return $this->tableName; }
}
