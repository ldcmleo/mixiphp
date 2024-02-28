<?php
/**
 * 
 * load all modules needed to run mixiphp app
 */

define('ROOT', dirname(dirname(__FILE__)) . "/");
// config folder
include_once ROOT."config/basic.php";
include_once ROOT."config/database.php";

// core folder
include_once ROOT."core/util/enum.php";
include_once ROOT."core/util/const.php";

include_once ROOT."core/db/Database.php";
include_once ROOT."core/db/Query.php";

include_once ROOT."core/routing/Argument.php";
include_once ROOT."core/routing/Request.php";
include_once ROOT."core/routing/Response.php";
include_once ROOT."core/routing/Router.php";
include_once ROOT."core/routing/Url.php";

include_once ROOT."core/app/Controller.php";
include_once ROOT."core/app/Model.php";
include_once ROOT."core/app/View.php";

// including variable files in /app/models folder
foreach (glob(ROOT."app/models/*.php") as $file) {
    include_once $file;
}