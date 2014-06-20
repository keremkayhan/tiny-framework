<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */ 

class Context
{
  
	protected static $instance = null;
	public $actionName = "";
	public $moduleName = "";
	public $request = "";
	
	public static function getInstance()
	{
		if( !self::$instance )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __construct()
	{
	}

  public function setActionName($action)
  {
	  $this->actionName = $action;
  }

  public function getActionName()
  {
	  return $this->actionName;
  }   
  
  public function setModuleName($module)
  {
	  $this->moduleName = $module;
  }

  public function getModuleName()
  {
	  return $this->moduleName;
  }
  
  public function getModuleActionName()
  {
	  return $this->moduleName.'_'.$this->actionName;
  } 
  
  public function getController()
  {
	  return new BaseController();
  } 

  public function setRequest($request)
  {
	  $this->request = $request;
  }  
  
  public function getRequest()
  {
	  return $this->request;
  } 

  public function moduleExists($module)
  {
	  return is_dir('modules/' . $module);
  }   
  
  public function set($name, $value)
  {
		$this->$name = $value;
  }
  
  public function get($name)
  {
		return $this->$name;
  }  
  
  public function has($name)
  {
    return !empty($this->$name);
  }   
  
	
  // Prevent users to clone the instance
  public function __clone()
  {
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }  
 
}