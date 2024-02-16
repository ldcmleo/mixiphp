<?php
namespace core\routing;
use core\util\ArgumentType;

/**
 * Class Argument
 * By: Leonardo Castro
 * 
 * Argument is the base custom type for system.
 * Basically argument is every string between / symbol in requested
 * or response routes.
 */
class Argument {
    private $type;
    private $key;
    
    public function __construct(String $key, $type = ArgumentType::Static) {
        if(!$key) {
            throw new \Exception("Class Argument expected at least 2 parameters but it received 1", 1);
        }

        $this->type = $type;
        $this->key = $key;
    }

    /**
     * Getters and Setters
     */
    public function setkey(String $key) { $this->key = $key; }
    public function getkey(bool $clean = false) {
        if($clean) return str_replace("@", "", $this->key);
        return $this->key; 
    }
    public function setType(ArgumentType $type) { $this->type = $type; }
    public function getType() { return $this->type; }
}
