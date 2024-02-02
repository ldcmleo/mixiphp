<?php
/**
 * 
 * All database configuration it's here, you can use it in every
 * part of code to use and conect to the database
 * 
 * by default: mysql/mariadb
 * 
 * * if you're using docker with compose configuration all database
 * * configuration is founded in:
 * * resources/compose.yml
 * ! By default DB_HOST is defined as a docker-compose file
 */

define('DB_HOST', 'db');
define('DB_USER', 'mixiphp');
define('DB_PASS', 'example123');
define('DB_NAME', 'appdb');

/**
 * 
 * * MixiPHP it's configure to use by default with
 * * MySQL/MariaDB
 * TODO: Create a wrapper to use with PostgreSQL and SQLServer
 */
define('DB_TYPE', 'mysql');
