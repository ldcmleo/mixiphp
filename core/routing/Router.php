<?php
namespace core\routing;
use core\app\View;
use core\util\ArgumentType;
/**
 * Class Router
 * By Leonardo Castro
 * 
 * Router manage all request and routes for system
 * also u can use get and post method functions to difference between these
 * two http methods
 */
class Router {
    private static $request;
    private static $routes = [];

    public static function go(string $responseName, array $arguments = NULL) {
        $resArray = [];

        foreach(self::$routes as $response) {
            if($response->getName() != $responseName) continue;
            if($response->getUrl()->size() == 0) return "/";

            $resArgs = $response->getUrl()->getArgs();
            $count = 0;
            foreach($resArgs as $resArg) {
                if($resArg->getType() == ArgumentType::Dynamic) {
                    if(!isset($arguments[$count])) return "/";

                    $resArray[] = $arguments[$count];
                    $count++;
                } else {
                    $resArray[] = $resArg->getKey();
                    $count++;
                }
            }
        }

        return count($resArray) ? implode("/", $resArray) : "/";
    }

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
        $response = new Response($url, "GET", $response);
        Router::$routes[] = $response;
        return $response;
    }

    public static function post($url, $response) {
        $response = new Response($url, "POST", $response);
        Router::$routes[] = $response;
        return $response;
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
