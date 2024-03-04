<?php
use core\routing\Router;
use core\routing\Request;
use core\app\Session;
/**
 * Front Controller on public folder
 * 
 * * This file is no needed to modify at logic business
 * Load all modules and create a request to process with
 * MixiPHP app with static class Router
 */
include_once "../mixi/core/run.php";
Session::start();
Router::setRequest(new Request((isset($_GET["url"]) ? $_GET["url"] : "/"), $_SERVER["REQUEST_METHOD"]));
Router::setRoutes($_SERVER["HTTP_USER_AGENT"]);
Router::execute();