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
        if($attributes) $this->setAttributes($attributes);
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
        if(!$this->getAttributes()) {
            throw new \Exception("Model " . get_class($this) . " need attributes to insert into table: " . $this->getTableName());
        }
        
        $columns = "";
        $values = "";

        foreach($this->getAttributes() as $column => $value) {
            $columns .= "$column, ";
            $values .= ":$column, ";
        }
        
        $columns = substr($columns, 0, -2);
        $values = substr($values, 0, -2);
        $tableName = $this->getTableName();

        $sql = "INSERT INTO `$tableName` ($columns) VALUES ($values)";

        $db = new Database;
        $db->query($sql);
        foreach($this->getAttributes() as $column => $value) {
            $db->bind(":$column", $value);
        }

        return $db->exec();
    }

    /**
     * remove
     * 
     * delete this model from the database
     * 
     * @param string $whereColumn column where clause from delete statement
     * @return boolean true on success, false on fail
     */
    public function remove($whereColumn = NULL) {
        if(!$this->getAttributes()) {
            throw new \Exception("Error, this model has no attributes");
        }

        if($whereColumn) {
            if(!$this->attrExists($whereColumn)) {
                throw new \Exception("Error, The parameter does not belong to the attributes of the model");
            }

            $column = strtolower($whereColumn);
            $tableName = $this->getTableName();
            $sql = "DELETE FROM `$tableName` WHERE $column = :$column";
            
            $db = new Database;
            $db->query($sql);
            $db->bind(":$column", $this->getAttribute($column));

            return $db->exec();
        }

        $pk = $this->getPrimaryKey();
        if(!$this->attrExists($pk)) {
            throw new \Exception("Error, Model has no primary key attribute");
        }

        $tableName = $this->getTableName();
        $sql = "DELETE FROM `$tableName` WHERE $pk = :$pk";
        $db = new Database;
        $db->query($sql);
        $db->bind(":$pk", $this->getAttribute($pk));

        return $db->exec();
    }

    /**
     * update
     * 
     * update database row match with @whereColumn with model attributes
     * 
     */
    public function update($whereColumn = NULL) {
        if(!$this->getAttributes()) {
            throw new \Exception("Model " . get_class($this) . " need attributes to insert into table: " . $this->getTableName());
        }

        if($whereColumn) {
            if(!$this->attrExists($whereColumn)) {
                throw new \Exception("Error, The parameter does not belong to the attributes of the model");
            }

            $whereColumn = strtolower($whereColumn);

            $sets = "";
            foreach($this->getAttributes() as $column => $value) {
                $sets .= "$column = :$column, ";
            }
            $sets = substr($sets, 0, -2);

            $tableName = $this->getTableName();
            $sql = "UPDATE `$tableName` SET $sets WHERE $whereColumn = :w$whereColumn";
            $db = new Database;
            $db->query($sql);
            foreach($this->getAttributes() as $column => $value) {
                $db->bind(":$column", $value);
            }
            $db->bind(":w$whereColumn", $this->getAttribute($whereColumn));

            return $db->exec();
        }

        $pk = $this->getPrimaryKey();
        if(!$this->attrExists($pk)) {
            throw new \Exception("Error, Model has no primary key attribute");
        }

        $sets = "";
        foreach($this->getAttributes() as $column => $value) {
            $sets .= "$column = :$column, ";
        }
        $sets = substr($sets, 0, -2);

        $tableName = $this->getTableName();
        $sql = "UPDATE `$tableName` SET $sets WHERE $pk = :w$pk";
        $db = new Database;
        $db->query($sql);
        foreach($this->getAttributes() as $column => $value) {
            $db->bind(":$column", $value);
        }
        $db->bind(":w$pk", $this->getAttribute($pk));

        return $db->exec();
    }

    public function get() { }
    public function build() { }

    public function getPrimaryKey() {
        $db = new Database;
        return $db->getPrimaryKey($this->tableName);
    }

    public function setAttributes(array $attributes) {
        foreach($attributes as $column => $value) {
            $this->setAttribute($column, $value);
        }
    }
    
    public function getAttributes() {
        return $this->attributes;
    }

    public function setAttribute($attribute, $value) {
        $this->attributes[$attribute] = $value;
    }

    public function getAttribute($attribute) {
        if(!key_exists($attribute, $this->attributes)) return;
        return $this->attributes[$attribute];
    }

    public function attrExists($attribute) {
        if(!$this->getAttribute($attribute)) return false;
        return true;
    }

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
     * table in BD need column named like model with snake case e.j. city_house
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
