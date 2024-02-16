<?php
namespace core\routing;
use core\util\ArgumentType;

/**
 * Class Url
 * By Leonardo Castro
 * 
 */
class Url {
    private $arguments = [];

    public function __construct(String $url) {
        rtrim($url);
        $arrayUrl = explode("/", $url);

        foreach ($arrayUrl as $value) {
            // deleting possible whitespaces like empty values in url
            if($value == "") continue;
            // reconfigure all about the url here
            $argument = $this->getArgType($value);
            $this->arguments[] = $argument;
        }
    }

    public function equals(Url $anotherUrl) {
        if($anotherUrl->size() == 0 && $this->size() == 0) return true;
        if($anotherUrl->size() != $this->size()) return false;
        foreach ($this->getArgs() as $key => $argument) {
            if($argument->getType() == ArgumentType::Dynamic) { continue; }

            if($argument->getkey() != $anotherUrl->getArg($key)->getkey()) return false;
        }
        return true;
    }

    private function getArgType(String $arg) {
        if(str_contains($arg, "@")) {
            return new Argument($arg, ArgumentType::Dynamic);
        }

        return new Argument($arg);
    }

    public function size() { return count($this->arguments); }
    public function getDynamicData($comparedUrl) {
        $data = [];
        foreach ($this->arguments as $key => $argument) {
            if($argument->getType() == ArgumentType::Dynamic) {
                $data[$argument->getkey(true)] = $comparedUrl->getArg($key)->getKey();
            }
        }

        return $data;
    }

    public function getArgs() { return $this->arguments; }
    public function setArgs($arguments) { $this->arguments = $arguments; }
    public function getArg($key) { return $this->arguments[$key]; }
}
