<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/**
 * TINY 
 * 
 * A simple application development framework for PHP 5
 * 
 * @package 	Tiny
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010-2013 Kerem Kayhan
 * @version		1.0
 */  

/* FOR WEB SITE TITLE */
define('PROJECT_NAME', 'TINY FRAMEWORK');

/* DIRECTORIES */
define('ROOT_DIRECTORY', getCurrentURL(true) . '/tiny_framework/');
define('WEB_ROOT', ROOT_DIRECTORY . '?/');

/* WITH HTACCESS 
define('ROOT_DIRECTORY', getCurrentURL(true) . '/');
define('WEB_ROOT', ROOT_DIRECTORY . '');
*/

/* DB Configuration */
define('DB_HOST', 'localhost');

define('DB_SCHEMA', 'ecokmed');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

/* ENVIRONMENT */
define('DEVELOPMENT_ENVIRONMENT',true);
if (DEVELOPMENT_ENVIRONMENT == true) { error_reporting(E_ALL); ini_set('display_errors','On'); } else { error_reporting(0); ini_set('display_errors','Off'); }

