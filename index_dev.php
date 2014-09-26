<?php 
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */
define('ACCESSIBLE', true);

session_start();

spl_autoload_register('autoload');
function autoload($className) {
  if( file_exists('lib/'.$className.'.php') ){
    require_once('lib/'.$className.'.php');
  }
}

require_once 'lib/Helper.php';
require_once '__Config.php';

$url = strchr($_SERVER['REQUEST_URI'], '?/');
$urlArray = explode('/',$url);

array_shift($urlArray);

/* WITH HTACCESS 
$url = strchr($_SERVER['REQUEST_URI'], '/');
$urlArray = preg_split('/[\/\\\]/',$url);
array_shift($urlArray);
*/

if( empty($urlArray[0]) ){
  $module = 'default';
}else{
  $module = $urlArray[0];
}

if( empty($urlArray[1]) ){
  $action = 'index';
}else{
  $action = $urlArray[1];
}

$queryString = array();
if( isset($urlArray[2]) ){
  $queryString = $urlArray[2];
}

$postParameters = array();
if( isset($_POST) ){
  $postParameters = $_POST; 
}

$request = new Request($queryString, $postParameters);

if( ! is_dir('modules/'.$module) ){
  if( DEVELOPMENT_ENVIRONMENT ){
    throw new Exception('No such MODULE: ' . strtoupper($module));
  }
  header('Location: 404.php');
  exit();
}

require_once('modules/'.$module . '/__Controller.php');
$controller = new Controller();

if( ! $controller->hasAction($action) && ! file_exists('modules/'.$module.'/'.$action.'.php') ){
  if( DEVELOPMENT_ENVIRONMENT ){
    throw new Exception('No such ACTION: ' . strtoupper($module.'/'.$action));
  }
  header('Location: 404.php');
  exit();
}

echo '<div style="background:red; color: white; padding: 4px; font-family: Verdana; text-align: center;">DEVELOPMENT_ENVIRONMENT</div>';
echo "<div id='sqlDebug'>".Database::getSqlDebug()."</div>";

$controller->dispatch($module, $action, $request);
$content = $controller->getContent();

if( $controller->layout ){
  require_once($controller->layout . '.php');
}else{
  echo $content;
}
Database::getSqlDebug();
?>
<script type="text/javascript">
<!--
$('body').prepend('<p style="background-color: orange; color: white; padding: 2px; text-align: center;">DEVELOPMENT_ENVIRONMENT</p>');
//-->
</script>