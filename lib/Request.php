<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */ 

class Request
{

  /***** CLASS CONSTANTS *****/

  const METHOD_HEAD = 'HEAD';
  const METHOD_GET = 'GET';
  const METHOD_POST = 'POST';
  const METHOD_PUT = 'PUT';
  const METHOD_DELETE = 'DELETE';
  
  protected $method;  
  protected $queryString = "";
  
	public function __construct($queryString, $postParameters)
	{
	  
		$this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
		
		if( count($queryString) > 0 ){
		  
			$qArray = explode("&",$queryString);
			foreach ( $qArray as $q ){
				$qItem = explode("=",$q);
				if( sizeof($qItem) > 1 ){
					$this->setParameter($qItem[0], $qItem[1]);
					
				}
			}
			$this->queryString = $queryString;
		}

		if( count($postParameters) > 0 ){
			foreach ( $postParameters as $key => $value ){
				$this->setParameter($key, $value);
			}
		}
		
	}
	
  public function setParameter($name,$value)
  {
		$this->$name = $value;
  }
  
  public function getParameter($name)
  {
    if( ! $this->hasParameter($name) ) return false;
		return $this->$name;
  }
  
  public function getQueryString()
  {
		return $this->queryString;
  }  
  
  public function hasParameter($name)
  {
		return isset($this->$name) ;
  }  
  
  public function getReferer()
  {
		return $_SERVER['HTTP_REFERER'];
  }
  
  public function __get($name)
  {
    if( !$this->hasParameter($name) ){
      return false;
    }
    return $this->getParameter($name);
  }      

 /***** HELPER METHODS *****/

  public function isGet() {
    return $this->method === self::METHOD_GET;
  }

  public function isPost() {
    return $this->method === self::METHOD_POST;
  }

  public function isPut() {
    return $this->method === self::METHOD_PUT;
  }

  public function isDelete() {
    return $this->method === self::METHOD_DELETE;
  }

  public function isHead() {
    return $this->method === self::METHOD_HEAD;
  }

  public function isAjax() {
    return ( $this->params('isajax') || $this->headers('X_REQUESTED_WITH') === 'XMLHttpRequest' );  
  }
  
}