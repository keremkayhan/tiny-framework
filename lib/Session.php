<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */ 

class Session
{
	protected static $instance = null;
	
	public static function getInstance()
	{
		if( !self::$instance )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
	}
  
  public function set($name,$value)
  {
		$_SESSION[$name] = $value;
  }
  
  public function get($name)
  {
    if( !isset($_SESSION[$name]) ){
      echo 'NO SUCH SESSION VARIABLE';
      return false;
    }
		return $_SESSION[$name];
  }
  
  public function remove($name)
  {
    unset($_SESSION[$name]);
  }  
  
  /*
   * @return Boolean
   **/
  public function has($name)
  {
    return !empty($_SESSION[$name]);
  }  
  
  public function getAll()
  {
    return $_SESSION;
  }   
  
}