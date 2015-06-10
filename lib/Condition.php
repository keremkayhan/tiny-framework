<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/** TINY - A simple application development framework for PHP 5
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010 Kerem Kayhan
 * @version		1.0
 */ 

class Condition
{
	public function add($field, $value)
	{
		$this->$field = cleanUpForSQL($value);
	}
	
	public function addJSON($field, $value)
	{
		$this->$field = json_encode($value);
	}
	
	public function remove($field)
	{
		$this->$field = null;
	}	
	
  public function getValues()
	{
		return get_object_vars($this);
	}	
}