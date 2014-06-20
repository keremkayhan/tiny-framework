<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */ 

require_once 'Session.php';

class Flash
{
  public static function setFlash($name,$value)
  {
    Session::getInstance()->set("flash_" . $name, $value);
  }
  
  public static function getFlash($name)
  {
    $flash = Session::getInstance()->get("flash_" . $name);
    Session::getInstance()->remove("flash_" . $name);
		return $flash;
  }
  
  public static function hasFlash($name)
  {
    return Session::getInstance()->has("flash_" . $name);
  }  
  
}