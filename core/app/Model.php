<?php
namespace core\app;
use core\db\Database;

/**
 * class Model
 * 
 * parent class for all models, with default functions to manage
 * all models with the database.
 */
class Model {
    private $tableName;
    private $attributes;
    
    public function __construct($attributes = NULL) {
        $this->tableName = $this->setTableName();
        if($attributes) $this->attributes = $attributes;
    }

    /**
     * use
     * 
     * retrieve a model child class with @model as name (name of file too)
     * u can also use traditional instance method e.j. $user = new User();
     * 
     * @param string $model name of file and class to be used
     * @return object instance of model class
     */
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
     * setTableName
     * configure tablename with the model child name
     * 
     * model name has pascal case and has the same name of file
     * e.j. CityHouse => CityHouse.php
     * 
     * for single model name e.j. "User" table name can be "user",
     * when the model name has more than one word, system explode it
     * and mix again with snake case e.j. CityHouse => city_house.
     * 
     * table in BD need name like snake case e.j. city_house
     * 
     * @return string name of database to match with this model
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
