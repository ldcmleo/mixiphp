<?php
namespace core\db;
use core\db\Database;

class Query {

    public static function execute(string $sql, array $params = NULL) {
        return self::build($sql, $params)->exec();
    }

    public static function getRows(string $sql, array $params = NULL) {
        return self::build($sql, $params)->getRows();
    }

    public static function get(string $sql, array $params = NULL) {
        return self::build($sql, $params)->getRow();
    }

    private static function build(string $sql, array $params = NULL) {
        $db = new Database;
        $db->query($sql);
        if($params)
            foreach($params as $param => $value) 
                $db->bind($param, $value);

        return $db;
    }

    /**
     * 
     * getPrimaryKey
     * obtain the primary key from specific table
     * 
     * @param string $tableName name of the table to find pk
     * @return string name of the primary key
     */
    public static function getPrimaryKey(string $tableName) {
        $db = new Database;
        $db->query("SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'");

        return $db->getRow()->Column_name;
    }
}