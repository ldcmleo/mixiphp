<?php
/**
 * 
 * load all modules needed to run mixiphp app
 */

// config folder
include_once "config/basic.php";
include_once "config/database.php";

// system folder
include_once "system/Argument.php";
include_once "system/Controller.php";
include_once "system/Database.php";
include_once "system/enum.php";
include_once "system/Models.php";
include_once "system/Request.php";
include_once "system/Response.php";
include_once "system/Router.php";
include_once "system/Url.php";
include_once "system/View.php";