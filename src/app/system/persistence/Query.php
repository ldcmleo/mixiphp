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

    public function __construct(QueryType $type) {
        $this->type = $type;

        $this->getTypeParameters();
    }

    private function getTypeParameters() {
        
    }
}
