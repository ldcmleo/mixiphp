<?php
/**
 * 
 * Class Database
 * 
 * Base class to use and access to the database.
 * Porting basic database function to use in app without 
 * database logic.
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db = DB_NAME;
    private $type = DB_TYPE;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db";

        $opciones = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $opciones);
            $this->dbh->exec("set names utf8");
        } catch (PDOException $e) {
            throw new Exception("Error at Database connection level <br> Description: " . $e->getMessage());
        }
    }

    /**
     * 
     * query
     * Prepares a statement for execution and returns a statement object.
     * @param string $sql 
     */
    public function query($sql) {
        try {
            $this->stmt = $this->dbh->prepare($sql); 
        } catch (Exception $e) {
            throw new Exception("Error at Database level <br> Description" . $e->getMessage());
        }
    }

    public function bind($param, $value, $type = NULL) {
        if (is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }
        
        try {
            $this->stmt->bindValue($param, $value, $type);
        } catch (Exception $e) {
            throw new Exception("Error at Database level <br> Description" . $e->getMessage());
        }
    }

    public function exec() { return $this->stmt->execute(); }

    public function getRows() {
        $this->exec();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRow() {
        $this->exec();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getRowCount() { return $this->stmt->rowCount(); }
}