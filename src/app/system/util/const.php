<?php
/**
 * 
 * Basic configuration from MixiPHP
 * * normally, at busines logic of the project,
 * * you don't need to touch this file
 */

// * Constants for basic directories
define('ROOT', (dirname(dirname(dirname(__FILE__)))) . "/");
define('CONTROLLERS', ROOT.'controllers/');
define('MODELS', ROOT.'models/');
define('VIEWS', ROOT.'views/');

// * Constants for specifically files
define('API_ROUTES', ROOT.'routes/api.php');
define('WEB_ROUTES', ROOT.'routes/web.php');

// * Constant for specifically user agents
define('MIXIPHP_API_AGENT', 'MixiPHP/1.0 API Access');
define('MIIXPHP_AJAX_AGENT', 'MixiPHP/1.0 AJAX Access');
