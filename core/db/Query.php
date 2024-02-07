<?php
/**
 * 
 * Class Query
 * 
 * Designed to contain and generate queries to
 * the database system
 */
class Query {
    private $type;
    private $parameters;
    private $tableName;

    public function __construct(QueryType $type, $tableName) {
        $this->type = $type;
        $this->tableName = $tableName;
        $this->parameters = $this->getTypeParameters();
    }

    /**
     * 
     * Implements a special keys for every query type
     * 
     * @return Array array with all index for different query type
     */
    private function getTypeParameters() {
        $params = array();
        if ($this->type == QueryType::Select) {
            $params["selected_rows"] = array();
        }
    }
}
