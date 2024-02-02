<?php
/**
 * 
 * load all modules needed to run mixiphp app
 */

// config folder
include_once "config/database.php";

// system folder
include_once "system/util/enum.php";
include_once "system/util/const.php";

include_once "system/persistence/Database.php";
include_once "system/persistence/Query.php";

include_once "system/routing/Argument.php";
include_once "system/routing/Request.php";
include_once "system/routing/Response.php";
include_once "system/routing/Router.php";
include_once "system/routing/Url.php";

include_once "system/Controller.php";
include_once "system/Model.php";
include_once "system/View.php";