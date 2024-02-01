<?php
/**
 * Front Controller on public folder
 * 
 * * This file is no needed to modify at logic business
 * Load all modules and create a request to process with
 * MixiPHP app with static class Router
 */
include_once "../app/loader.php";
$url = isset($_GET["url"]) ? $_GET["url"] : "/";
$request = new Request($url, $_SERVER["REQUEST_METHOD"]);

Router::setRequest($request);

// TODO: read user-agent to determine if is web or api
include_once "../app/routes/web.php";

Router::execute();