<?php
/**
 * 
 * 
 */
class Response {
    private $url;
    private $method;
    private $function;

    public function __construct($url, $method, $function) {
        if(!$method || !$function) {
            throw new Exception("Response Expected 3 arguments");
        }

        $this->url = new Url($url);
        $this->method = $method;
        $this->function = $function;
    }

    public function use() {
        if(is_null($this->function)) {
            throw new Exception("Response has not function declared");
        }

        if(is_callable($this->function)) return $this->function;

        if(is_string($this->function)) {
            if(!str_contains($this->function, "@")) {
                throw new Exception("Error while procesing response, use @ to divide functions from class on String response function", 1);
            }

            $function = explode("@", $this->function);
            $controller = Controller::use($function[0]);

            if(!method_exists($controller, $function[1])) {
                throw new Exception("Method " . $function[1] . " doesn't exist, on Controller: " . $function[0]);
            }

            return [$controller, $function[1]];
        }
    }

    /**
     * Getters and Setter
     */
    public function getUrl() { return $this->url; }
    public function setUrl($url) { $this->url = $url; }
    public function getMethod() { return $this->method; }
    public function setMethod($method) { $this->method = $method; }
}
