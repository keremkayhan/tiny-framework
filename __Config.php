<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');

/* ENVIRONMENT */
$is_dev = true;

if( strpos($_SERVER["REQUEST_URI"], 'index_dev.php') ){ $is_dev = true; }
define('DEVELOPMENT_ENVIRONMENT', $is_dev);
if (DEVELOPMENT_ENVIRONMENT == true) { error_reporting(E_ALL); ini_set('display_errors','On'); } else { error_reporting(0); ini_set('display_errors','Off'); }

/* FOR WEB SITE TITLE */
define('PROJECT_NAME', 'TINY FRAMEWORK');

/* DIRECTORIES */
define('ROOT_DIRECTORY', 'http://'.$_SERVER["SERVER_NAME"] . '/tiny-framework/');
define('WEB_ROOT', ROOT_DIRECTORY . '?/');

/* DB Configuration */
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_SCHEMA', 'test');

/* HASH and ENCRYPTION */
define('USE_HASH', false);
define('ENC_KEY', 'MVMOCTprW0BFBssAo0iWn6exfuG46Xi');

/* COOKIE Configuration */
$cookie_params = session_get_cookie_params();
session_set_cookie_params($cookie_params['lifetime'], $cookie_params['path'], $cookie_params['domain'], true, true);

date_default_timezone_set("Europe/Istanbul");