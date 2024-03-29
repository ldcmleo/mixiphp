<?php
namespace core\app;
use core\db\Query;

/**
 * class Model
 * 
 * parent class for all models, with default functions to manage
 * all models with the database.
 */
class Model extends \StdClass {
    protected $tableName;
    protected $selectQuery;
    
    public function __construct($attributes = NULL) {
        $this->tableName = $this->setTableName();
        if($attributes) $this->setAttributes($attributes);
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
        $arrayColumns = array();
        $values = "";
        $arrayValues = array();
        $arrayBind = array();

        foreach($this->getAttributes() as $column => $value) {
            $arrayColumns[] = $column;
            $arrayValues[] = ":$column";
            $arrayBind[":$column"] = $value;
        }
        
        $columns = implode(", ", $arrayColumns);
        $values = implode(", ", $arrayValues);
        $tableName = $this->getTableName();

        $sql = "INSERT INTO `$tableName` ($columns) VALUES ($values)";

        return Query::execute($sql, $arrayBind);
    }

    /**
     * remove
     * 
     * delete actual instance model from the database
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
            
            return Query::execute($sql, [
                ":$column" => $this->getAttribute($column)
            ]);
        }

        $pk = $this->getPrimaryKey();
        if(!$this->attrExists($pk)) {
            throw new \Exception("Error, Model has no primary key attribute");
        }

        $tableName = $this->getTableName();
        $sql = "DELETE FROM `$tableName` WHERE $pk = :$pk";

        return Query::execute($sql, [
            ":$pk" => $this->getAttribute($pk)
        ]);
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
            $arraySets = array();
            $arrayBind = array();
            foreach($this->getAttributes() as $column => $value) {
                $arraySets[] = "$column = :$column";
                $arrayBind[":$column"] = $value; 
            }
            $sets = implode(", ", $arraySets);

            $tableName = $this->getTableName();
            $sql = "UPDATE `$tableName` SET $sets WHERE $whereColumn = :w$whereColumn";
            $arrayBind[":w$whereColumn"] = $this->getAttribute($whereColumn);

            return Query::execute($sql, $arrayBind);
        }

        $pk = $this->getPrimaryKey();
        if(!$this->attrExists($pk)) {
            throw new \Exception("Error, Model has no primary key attribute");
        }

        $sets = "";
        $arraySets = array();
        $arrayBind = array();
        foreach($this->getAttributes() as $column => $value) {
            $arraySets[] = "$column = :$column";
            $arrayBind[":$column"] = $value;
        }
        $sets = implode(", ", $arraySets);

        $tableName = $this->getTableName();
        $sql = "UPDATE `$tableName` SET $sets WHERE $pk = :w$pk";
        $arrayBind[":w$pk"] = $this->getAttribute($pk);

        return Query::execute($sql, $arrayBind);
    }

    /**
     * find
     * search for a specific row in database model -> tablename
     * 
     * @param mix $id identifier of the searched row
     */
    public static function find($id) {
        $newModel = get_called_class();
        $newModel = new $newModel;
        $tableName = $newModel->getTableName();
        $pk = $newModel->getPrimaryKey();

        $sql = "SELECT * FROM $tableName WHERE $pk = :$pk";

        $result = Query::get($sql, [":$pk" => $id]);

        if(!$result) return NULL;
        $newModel->setAttributes((array) $result);
        return $newModel;
    }

    /**
     * select
     * 
     */
    public static function select($columns = NULL) {
        if(!is_array($columns) && !is_string($columns) && !is_null($columns)) {
            $newModel = get_called_class();
            $newModel = new $newModel;
            return $newModel;
        }

        if(is_string($columns)) $columns = explode(" ", $columns);

        $newModel = get_called_class();
        $newModel = new $newModel;
        if($columns) $newModel->selectQuery["select"] = $columns;
        return $newModel;
    }

    public function where(Array $conditions) {
        $result = NULL;
        if(!is_array($conditions[0])) {
            $conditions = [
                $conditions
            ];
        }

        foreach($conditions as $condition) {
            $result[] = [
                $condition[0], 
                isset($condition[2]) ? $condition[1] : "=", 
                isset($condition[2]) ? $condition[2] : $condition[1]
            ];
        }

        $this->selectQuery["where"] = [
            "type" => "AND",
            "conditions" => $result
        ];
        return $this;
    }

    public function orWhere(Array $conditions) {
        $result = NULL;
        if(!is_array($conditions[0])) {
            $conditions = [
                $conditions
            ];
        }

        foreach($conditions as $condition) {
            $result[] = [
                $condition[0], 
                isset($condition[2]) ? $condition[1] : "=", 
                isset($condition[2]) ? $condition[2] : $condition[1]
            ];
        }

        $this->selectQuery["where"] = [
            "type" => "OR",
            "conditions" => $result
        ];
        return $this;
    }

    public function and(Array $condition) {
        $result = [
            $condition[0], 
            isset($condition[2]) ? $condition[1] : "=", 
            isset($condition[2]) ? $condition[2] : $condition[1]
        ];
        $this->selectQuery["extraWhere"][] = [
            "type" => "AND",
            "value" => $result
        ];
        return $this;
    }

    public function or(Array $condition) {
        $result = [
            $condition[0], 
            isset($condition[2]) ? $condition[1] : "=", 
            isset($condition[2]) ? $condition[2] : $condition[1]
        ];
        $this->selectQuery["extraWhere"][] = [
            "type" => "OR",
            "value" => $result
        ];
        return $this;
    }

    public function in(string $columnName, Array $values) { 
        $this->selectQuery["in"][] = [
            "type" => "AND",
            "column" => $columnName, 
            "values" => $values
        ];
        return $this;
    }

    public function orIn(string $columnName, Array $values) {
        $this->selectQuery["in"][] = [
            "type" => "OR",
            "column" => $columnName,
            "values" => $values
        ];

        return $this;
    }

    public function like($column, $wildcar) { 
        $this->selectQuery["like"][] = [
            "type" => "AND",
            "column" => $column,
            "value" => $wildcar
        ];
        return $this;
    }

    public function orLike($column, $wildcar) { 
        $this->selectQuery["like"][] = [
            "type" => "OR",
            "column" => $column,
            "value" => $wildcar
        ];
        return $this;
    }

    public function take(int $nrows) { 
        $this->selectQuery["limit"] = $nrows;
        return $this;
    }

    public function order($columns) {
        $result = [];
        if(is_string($columns)) {
            $result[] = [$columns, "ASC"];
        } else if(is_array($columns) and !is_array($columns[0])) {
            foreach($columns as $col) {
                $result[] = [$col, "ASC"];
            }
        } else if(is_array($columns) and is_array($columns[0])) {
            foreach($columns as $col) {
                $result[] = [$col[0], (isset($col[1]) ? $col[1] : "ASC")];
            }
        } else {
            return $this;
        }

        $this->selectQuery["order"] = $result;
        return $this;
    }

    public function min($columnName) { 
        $this->selectQuery["function"] = ["type" => "min", "column" => $columnName];
        return $this;
    }

    public function max($columnName) {
        $this->selectQuery["function"] = ["type" => "max", "column" => $columnName];
        return $this;
    }

    public function count($columnName = NULL) {
        $this->selectQuery["function"] = ["type" => "count", "column" => ($columnName ? $columnName : "*")];
        return $this;
    }

    public function sum($columnName) {
        $this->selectQuery["function"] = ["type" => "sum", "column" => $columnName];
        return $this;
    }

    public function avg($columnName) {
        $this->selectQuery["function"] = ["type" => "avg", "column" => $columnName];
        return $this;
    }

    public function join($type, $otherTable, $column1, $column2) { 
        $this->selectQuery["join"] = [
            "type" => $type,
            "otherTable" => $otherTable,
            "columnTable1" => $column1,
            "columnTable2" => $column2
        ];
        return $this;
    }

    public function innerJoin($otherTable, $column1, $column2) {
        return $this->join("INNER", $otherTable, $column1, $column2);
    }

    public function leftJoin($otherTable, $column1, $column2) {
        return $this->join("LEFT", $otherTable, $column1, $column2);
    }

    public function rightJoin($otherTable, $column1, $column2) {
        return $this->join("RIGHT", $otherTable, $column1, $column2);
    }

    public function fullJoin($otherTable, $column1, $column2) {
        return $this->join("FULL OUTER", $otherTable, $column1, $column2);
    }

    /**
     * get
     * retrieve the select query with the database results
     * 
     */
    public function get() { 
        $tableName = $this->getTableName();
        $select = "";
        $where = "";
        $join = "";
        $order = "";
        $limit = "";
        $sql = "";
        $binds = [];
        $args = $this->selectQuery;

        try {
            if(isset($args["select"])) {
                $select = implode(", ", $args["select"]);
            }

            if(isset($args["function"])) {
                $func = strtoupper($args["function"]["type"]);
                $column = $args["function"]["column"];
                $select = "$func($column)";
            }
    
            if(isset($args["where"])) {
                $logicOperator = strtoupper($args["where"]["type"]);
                $conditions = $args["where"]["conditions"];
                $operations = [];
                foreach($conditions as $condition) {
                    $column = $condition[0];
                    $operator = $condition[1];
                    $value = $condition[2];
                    $whereID = count($binds);
                    $operations[] = "$column $operator :w$column$whereID";
                    $binds[":w$column$whereID"] = $value; 
                }
    
                $where = implode(" $logicOperator ", $operations);
            }

            if(isset($args["in"])) {
                foreach ($args["in"] as $in) {
                    $logicOperator = strtoupper($in["type"]);
                    $column = $in["column"];
                    $values = [];
                    foreach($in["values"] as $number => $value) {
                        $values[] = ":in$column$number";
                        $binds[":in$column$number"] = $value;
                    }

                    $values = implode(", ", $values);

                    if(!$where) {
                        $where .= "$column IN ($values)";
                    } else {
                        $where .= " $logicOperator $column IN ($values)";
                    }
                }
            }

            if(isset($args["like"])) {
                foreach($args["like"] as $number => $like) {
                    $logicOperator = strtoupper($like["type"]);
                    $column = $like["column"];
                    $value = $like["value"];
                    if(!$where) {
                        $where .= "$column LIKE :like$column$number";
                    } else {
                        $where .= " $logicOperator $column LIKE :like$column$number";
                    }

                    $binds[":like$column$number"] = $value;
                }
            }

            if(isset($args["extraWhere"])) {
                foreach ($args["extraWhere"] as $condition) {
                    $type = $condition["type"];
                    $values = $condition["value"];
                    $column = $values[0];
                    $operator = $values[1];
                    $value = $values[2];
                    $whereID = count($binds);
                    if(!$where) {
                        $where .= "$column $operator :w$column$whereID";
                    } else {
                        $where .= " $type $column $operator :w$column$whereID";
                    }

                    $binds[":w$column$whereID"] = $value;
                }
            }

            if(isset($args["join"])) {
                $type = $args["join"]["type"];
                $table2 = $args["join"]["otherTable"];
                $column1 = $args["join"]["columnTable1"];
                $column2 = $args["join"]["columnTable2"];
                $selectCols = [];
                if($select) {
                    $allColumns = explode(", ", $select);
                    foreach($allColumns as $column) {
                        if($column1 == $column) continue;
                        $selectCols[] = "$tableName.$column";
                    }
                }
                $selectCols[] = "$tableName.$column1";
                $selectCols[] = "$table2.$column2";

                $select = implode(", ", $selectCols);
                $join = "$type JOIN `$table2` ON $tableName.$column1 = $table2.$column2";
            }

            if(isset($args["order"])) {
                $columns = $args["order"];
                $values = [];
                foreach($columns as $cols) {
                    $values[] = ":o" . $cols[0] . " " . strtoupper($cols[1]);
                    $binds[":o" . $cols[0]] = $cols[0];
                }

                $order = implode(", ", $values);
            }

            if(isset($args["limit"])) {
                $limit = "LIMIT " . $args["limit"];
            }
    
            $select = $select ? $select : "*";
            $where = $where ? "WHERE $where" : "";
            $order = $order ? "ORDER BY $order" : "";

            $sql = trim("SELECT $select FROM `$tableName` $join $where $order $limit");
            // print_r($sql);
            $results = Query::getRows($sql, $binds);
            if($results) {
                if(count($results) > 1) {
                    $resultArray = [];
                    foreach($results as $result) {
                        $newModel = get_called_class();
                        $newModel = new $newModel((array) $result);
                        $resultArray[] = $newModel;
                    }
                    return $resultArray;
                }
                $newModel = get_called_class();
                $newModel = new $newModel((array) $results[0]);
                return $newModel;
            }
        } catch (\Throwable $th) {
            throw new \Exception("Error with get method in Model " . $this->getTableName() . " <br> Error: " . $th->getMessage());
        }
    }

    public function getPrimaryKey() {
        return Query::getPrimaryKey($this->getTableName());
    }

    public function setAttributes(array $attributes) {
        foreach($attributes as $column => $value) {
            $this->{$column} = $value;
        }
    }
    
    public function getAttributes() {
        $attributes = get_object_vars($this);
        unset($attributes["tableName"]);
        unset($attributes["selectQuery"]);
        return $attributes;
    }

    public function setAttribute($attribute, $value) {
        $this->{$attribute} = $value;
    }

    public function getAttribute($attribute) {
        return isset($this->{$attribute}) ? $this->{$attribute} : "";
    }

    public function removeAttribute($attribute) {
        if(!$this->attrExists($attribute)) return;
        $value = $this->{$attribute};
        unset($this->{$attribute});
        return $value;
    }

    public function attrExists($attribute) {
        return isset($this->{$attribute});
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
        foreach($modelNameAsArray as $key => $word) {
            $modelNameAsArray[$key] = strtolower($word);
        }

        return implode("_", $modelNameAsArray);
    }

    /**
     * getTableName
     * 
     * @return string name of the represented table in the database
     */
    public function getTableName() { return $this->tableName; }
}
