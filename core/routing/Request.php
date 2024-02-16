<?php
namespace core\routing;

/**
 * 
 * Class Request
 * By: Leonardo Castro
 * 
 * A simple POO based class to control 
 * request from front controller
 */
class Request {
    private $url;
    private $method;

    public function __construct(String $url, String $method = "GET") {
        if (!$url) throw new \Exception("Request added without url, verify your routes");
        $this->method = $method;
        $this->url = new Url($url);
    }

    /**
     * Getter and Setters
     */
    public function getUrl() { return $this->url; }
    public function setUrl($url) { $this->url = $url; }
    public function getMethod() { return $this->method; }
    public function setMethod($method) { $this->method = $method; }
}
