<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */ 

class BaseController
{
  
	public $layout = "layout";
  
  public function dispatch($module, $action, Request $request = null)
	{
	  
		if( file_exists('modules/'.$module.'/__Config.php') ){
      $config = require 'modules/'.$module.'/__Config.php';
      if( ! isset($config['public']) ){
        if($config['set_secure']){ $this->setSecure($config['credential']); }
      }else{
        if($config['set_secure'] && ! in_array($action, $config['public'])){ $this->setSecure($config['credential']); }
      }
      if( isset($config['layout']) ){
        $this->setLayout($config['layout']);
      }
	  }
	  
	  Context::getInstance()->setModuleName($module);
	  Context::getInstance()->setActionName($action);
	  Context::getInstance()->setRequest($request);
	  Context::getInstance()->set('title', PROJECT_NAME);
	  
  	if( method_exists($this, $action) )
    {
      
      $this->$action($request);
  		$this->content = $this->setTemplate($module, $action, $request);
  		
    }else{
      
      if( file_exists('modules/'.$module.'/'.$action.'.php') ){
        $this->content = $this->setTemplate($module, $action, $request);
        return false;
      }
      
      if( DEVELOPMENT_ENVIRONMENT ){
        throw new Exception('No such action: ' . strtoupper($action));
      }else{
        $this->redirect404();
      }
    }	  
    
	}
	
  public function setTemplate($module, $action, Request $request = null)
  {
    
    if( ! file_exists('modules/'.$module.'/'.$action.'.php') ){
      exit();
      return false;
    }
    
		$retVal = "";
		$variables = get_object_vars($this);
		
		foreach ($variables as $key => $value)
		{
			$$key = $value;
		}
		
		ob_start();
		include ('modules/'.$module.'/'.$action.'.php');
		$retVal = ob_get_contents();
		ob_end_clean();		
		
		return $retVal;
		
  }
	
	public function getContent()
	{
		return $this->content;
	}
	
	public function setContent($content)
	{
		$this->content = $content ;
	}

	public function redirect($module_action, $params = null)
	{
	  if( '404' == $module_action ){
	    header('Location: 404.php');
	    exit();
	  }
	  header('Location: ' . url_for($module_action, $params));
	  exit();
	}	
	
	public function redirect404()
	{
	  //header("HTTP/1.0 404 Not Found");
    $this->redirect('404');
	}
	
	public function redirect404Unless($condition)
	{
    if( $condition == false || $condition == '' ){
      $this->redirect('404');
    }
	}	
	
  public function redirectReferer(Request $request)
	{
	  header('Location: ' . $request->getReferer());
	  exit();
	}

	public function setSecure($credential = null)
	{
  	if( false == User::getInstance()->isAuthenticated() && ! isset($_COOKIE[slugify(PROJECT_NAME)."_remember_me"]) ){
  	  if( Context::getInstance()->moduleExists('user') ){  
  	    Flash::setFlash('ref', $_SERVER['REQUEST_URI']);
        $this->redirect('user/login');
  	  }else{
  	    throw new Exception('--- This action is SECURE ---' );
  	  }
    }
    
	  if( isset($_COOKIE[slugify(PROJECT_NAME)."_remember_me"]) ){
      $user = Database::getTable('user')->findOneBy('remember_me', $_COOKIE[slugify(PROJECT_NAME)."_remember_me"]);
      User::getInstance()->authenticate($user);
    }    

	  if( $credential && !User::getInstance()->hasCredential($credential) && !User::getInstance()->hasCredential('superadmin') ){
      $this->redirect('user/secure');
	    exit();
    }    
	}

	public function hasAction($action)
	{
	  if( method_exists($this, $action) || file_exists($action.'.php') ){
	    return true;
	  }
	  return false;
	}
	
	public function setLayout($layout)
	{
	  $this->layout = $layout;
	}

}