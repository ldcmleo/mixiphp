<?php
/**
 * 
 * Basic configuration from MixiPHP
 * * normally, at busines logic of the project,
 * * you don't need to touch this file
 */

define('ROOT', dirname(dirname(dirname(__FILE__))));
define('CONTROLLERS', ROOT.'/app/controllers/');
define('MODELS', ROOT.'/app/models/');
define('VIEWS', ROOT.'/app/views/');

define('API_ROUTES', ROOT.'/app/routes/api.php');
define('WEB_ROUTES', ROOT.'/app/routes/web.php');
define('MIXIPHP_AGENT', 'MixiPHP/v1.0 API Access');
