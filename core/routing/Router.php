<?php
namespace core\routing;
use core\app\View;
/**
 * 
 * 
 */
class Router {
    private static $request;
    private static $routes = [];

    public static function setRequest(Request $request) {
        Router::$request = $request;
    }

    public static function setRoutes(String $userAgent) {
        if($userAgent == MIXIPHP_API_AGENT) {
            return include_once API_ROUTES;
        }
        
        return include_once WEB_ROUTES;
    }

    public static function get($url, $response) {
        Router::$routes[] = new Response($url, "GET", $response);
    }

    public static function post($url, $response) {
        Router::$routes[] = new Response($url, "POST", $response);
    }

    public static function execute() {
        try {
            $response = Router::getResponse();
            $responseFunction = $response->use();
            $data = $response->getUrl()->getDynamicData(Router::$request->getUrl());

            call_user_func_array($responseFunction, $data);
        } catch (\Exception $error) {
            return View::use("500", ["error" => $error->getMessage()]);
        }
    }

    public static function getResponse() {
        foreach (Router::$routes as $route) {
            if($route->getUrl()->equals(Router::$request->getUrl())) {
                return $route;
            }
        }

        throw new \Exception("Page not Found");
    }
}
