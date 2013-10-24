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

class Database 
{
	/** CLASS CONSTANTS **/
	
	protected $connection = null;

	protected $table;
	
	protected $fields = "*";

	protected $columns = array();

	protected $sql;
	
	protected static $sqlDebug = "<b>SQL QUERIES</b><br><br>";
	protected static $sqlCount = 0;
	
  public function __construct()
  {
		$this->connection = ConnectionManager::getInstance()->getConnection('default');
  }	
	
	public function getConnection()
	{
		return $this->connection;
	}	
	
	private function setSqlDebug($sql, $params)
	{
		if( strpos($sql, "DESCRIBE") !== 0 ){
			self::$sqlCount++;
			self::$sqlDebug .= '<li style="border-bottom: 1px solid #999999; padding: 4px 0">' . $this->getHumanQuery($sql, $params) . '</li>';
		}
	}		
	
	public static function getSqlDebug()
	{
		echo "<ol style='background: #F7F7F7; border: 2px solid #DDDDDD; padding: 10px; color: #666666; font-family: verdana; font-size: 12px; padding-left: 40px'>".self::$sqlDebug."</ol>";
	}	
	
	public function setTable($table)
	{
		$this->table = $table;
	}		
	
  public function retreiveTable()
  {
    return $this->table;
  }	
	
	public static function getTable($table)
	{
		$db = new Database();
		$db->table = $table;
		return $db;
	}
	
	
	public static function executeSQL($sql, $getOne = false)
	{
		$db = new Database();
		if( $getOne ){
		  $retVal = $db->run($sql)->fetch(PDO::FETCH_ASSOC);
		}else{
		  $retVal = $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		}
		return $retVal;
	}	
		
	/* MAGICS */
	
	public function findAll($fields = array(), $orderBy = null, $limit = null)
	{
		$this->select($fields);

		$sql = "SELECT " . $this->fields . " FROM ". $this->table;
		
		if( $orderBy ){	$sql .= " ORDER BY " . $orderBy; }
		if( $limit ){ $sql .= " LIMIT " . $limit;	}
		
		$result = $this->run($sql)->fetchAll(PDO::FETCH_ASSOC); 

		return $result;
	}
	
	public function countAll()
	{
		$sql = "SELECT count(*) as count FROM ". $this->table;
		$result = $this->run($sql)->fetch(PDO::FETCH_ASSOC);
		return $result['count'];
	}		

  public function findColumns()
  {
    return $this->getColumns($this->table);
  }	
	
	public function find($id, $fields = array())
	{
		$this->select($fields);
		$sql = "SELECT " . $this->fields . " FROM ". $this->table . " WHERE id = ?";
		
		$result = $this->run($sql, array($id))->fetch(PDO::FETCH_ASSOC);
		
		if( ! $result ){ return false; }
		
		return $result;
	}		
	
	public function findBy($field, $value, $fields = array(), $orderBy = null, $limit = null)
	{
		if ( ! isset($field) || ! isset($value)) { throw new Exception('FIELD OR VALUE CAN NOT BE NULL'); }	  
    
		$this->select($fields);
		
		$sql = "SELECT " . $this->fields . " FROM ". $this->table . " WHERE `" . $field . "` = ?";
		
	  if( $orderBy ){
      if( $orderBy == 'RAND()' ){
        $sql .= " ORDER BY " . $orderBy;
      }else{
        $sql .= " ORDER BY `" . $orderBy . "`";
      }
    }
    if( $limit ){ $sql .= " LIMIT " . $limit; }   		
		
    $result = $this->run($sql, array($value))->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function findLike($field, $value, $fields = array(), $orderBy = null, $limit = null)
	{
		if ( ! isset($field) || ! isset($value)) { throw new Exception('FIELD OR VALUE CAN NOT BE NULL'); }	  
	  
		$this->select($fields);
		
		$sql = "SELECT " . $this->fields . " FROM ". $this->table . " WHERE `" . $field . "` LIKE ?";
		
	  if( $orderBy ){
      if( $orderBy == 'RAND()' ){
        $sql .= " ORDER BY " . $orderBy;
      }else{
        $sql .= " ORDER BY `" . $orderBy . "`";
      }
    }
    if( $limit ){ $sql .= " LIMIT " . $limit; }   		
		
    $result = $this->run($sql, array('%' . $value . '%'))->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}	
	
  public function findOneBy($field, $value, $fields = array(), $orderBy = null, $limit = null)
  {
  	$result = $this->findBy($field, $value, $fields = array());
  	if( count($result) == 0 ){ return false; }
  	return $result[0];
  } 	
	
	public function findConditionally(Condition $condition, $fields = array(), $orderBy = null, $limit = null)
	{
		$values = get_object_vars($condition);
		
		$conditionStr = "";
		$conditions = array();
		
		$params = array();
		
		foreach ($values as $key => $value) 
		{
			if( $this->hasColumn($this->table, $key) == false ){
				throw new Exception('NO ' . strtoupper($key) . ' IN ' . strtoupper($this->table));
			}
			$conditions[] = "`" . $key. "` = ?";
			$params[] = $value;
		}
		
		$conditionStr = implode(" AND ", $conditions);
		
		$this->select($fields);
		$sql = "SELECT " . $this->fields . " FROM ". $this->table . " WHERE " . $conditionStr;
	  
		if( $orderBy ){ $sql .= " ORDER BY " . $orderBy; }
    if( $limit ){ $sql .= " LIMIT " . $limit; }		
		
		$result = $this->run($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}		
	
	public function findOneConditionally(Condition $condition, $fields = array())
	{
    $result = $this->findConditionally($condition, $fields = array());
    if(empty($result)){ return false; }
    return $result[0];
	}

	
	private function select($fields)
	{
		$retFields = array();

		if( empty($fields) ){
			$fields = $this->getColumns($this->table);
		}
		foreach ($fields as $field) {
			$retFields[] = $this->table.".".$field;
		}			
		$this->fields = implode(', ', $retFields);
	}	

	
	private function getFields($table)
	{
		$retFields = array();

		$fields = $this->getColumns($table);

		foreach ($fields as $field) {
			$retFields[] = $table . "." . $field ." AS " . $table . "_" . $field;
		}
		return implode(', ', $retFields);
	}	

  private function getColumns($table)
  {
  	$sql = "DESCRIBE ". $table;
		$result = $this->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		$fields = array();
		foreach ($result as $arr) {
			$fields[] = $arr['Field'];
		}
		return $fields;
  }  
  
  private function hasColumn($table, $column)
  {
		$fields = $this->getColumns($table);
		return array_search($column, $fields);
  }
  
  
  public function save(Condition $condition)
  {
  	$vars = get_object_vars($condition);
  	
  	$vars = self::sortArrayByArray($vars, $this->getColumns($this->table));

  	$fields = array();
  	$values = array();
  	$updates = array();
  	$place_holders = array();
  	
  	foreach ($vars as $key => $value) {
  		$fields[] = "`" . $key . "`";
  		$values[] = $value;
  		$place_holders[] = '?';
  		if( $key != "id" ){
  			if( strtolower($value) == 'null' ){
  				$updates[] = $key." = NULL";
  			}else{
  				$updates[] = "`" . $key . "` = ?";
  			}
  		}
  	}
  	
  	if( $this->hasColumn($this->table, 'updated_by') ){
			$fields[] = 'updated_by';
  		$values[] = User::getInstance()->id;  		
  		$updates[] = User::getInstance()->id;
  		$place_holders[] = '?';
  	}  	
  	
  	/*TODO: NOT ID, BUT PRI KEY*/
  	if(array_key_exists("id", $vars)){
    	
  	  ############### U P D A T E ###############
  	  
    	/* ACT AS TIMESTAMPABLE IF REQUIRED FIELDS EXIST */
  	  if( $this->hasColumn($this->table, 'updated_at') ){
  			$values[] = date('Y-m-d H:i:s');
    		$updates[] = 'updated_at = ?';
    	}
    	
  	  /* ACT AS SIGNABLE IF REQUIRED FIELDS EXIST */
  	  if( $this->hasColumn($this->table, 'updated_by') ){
  			$values[] = User::getInstance()->id;
    		$updates[] = 'updated_by = ?';
    	}    	
      
    	
    	
    	$updateStr = implode(", ", $updates);	
  		
    	$sql = "UPDATE ".$this->table." SET " . $updateStr . " WHERE id = ?";
  		
    	$values[] = $vars['id'];
    	
    	array_shift($values);
    	
    	
    	
  		$effected_id = $vars['id'];
			$this->run($sql, $values);  
			return $effected_id;  		
  	
  	}else{
  	  
  	  ############### I N S E R T ############### 
    	
    	/* ACT AS TIMESTAMPABLE IF REQUIRED FIELDS EXIST */
      if( $this->hasColumn($this->table, 'created_at') ){
    		$fields[] = 'created_at';
    		$values[] = date('Y-m-d H:i:s');
    		$place_holders[] = '?';
    	}  	
      if( $this->hasColumn($this->table, 'updated_at') ){
    		$fields[] = 'updated_at';
    		$values[] = date('Y-m-d H:i:s');
    		$place_holders[] = '?';
    	} 

      /* ACT AS SIGNABLE IF REQUIRED FIELDS EXIST */
      if( $this->hasColumn($this->table, 'created_by') ){
    		$fields[] = 'created_by';
    		$values[] = User::getInstance()->id;
    		$place_holders[] = '?';
    	}     	
  	  
  	  $sql = "INSERT INTO ".$this->table." (". implode(", ", $fields) . ") VALUES (". implode(", ", $place_holders).")";
			$this->run($sql, $values);  
			return $this->getConnection()->lastInsertId();  		
  	}
  	
  }
  
  public function delete($condition)
  {
  	if( !isset($condition) ){
  		return false;
  	}
  	
  	$values = array();
  	
  	$vars = array("id" => $condition);
  	
  	if ($condition instanceof Condition) {
  		$vars = get_object_vars($condition);	
  	}
  	
  	$conditions = array();
  	
  	foreach ($vars as $key => $value) {
 			$conditions[] = $key." = ?";
 			$values[] = $value;
  	}
  	
  	$conditionStr = implode(" AND ", $conditions);
  	
 		$sql = "DELETE FROM " . $this->table . " WHERE " . $conditionStr;
		
 		$this->run($sql, $values);

  }  
  
  private function run($sql, $params = array())
  {
    $statement = $this->getConnection()->prepare($sql);
		$statement->execute($params);
		$this->setSqlDebug($sql, $params);
		return $statement;
  }
  
  private function getHumanQuery($query, $params) 
  {
    $values = array(); 
    foreach ($params as $param) {
      $values[] = "'" . $param . "'";
    }
    
    $keys = array();
    foreach ($params as $key => $value) {
      if (is_string($key)) {
        $keys[] = '/:'.$key.'/';
      } else {
        $keys[] = '/[?]/';
      }
    }
    $query = preg_replace($keys, $values, $query, 1, $count);
    
    return $query;
  }  
  
  
  private function sortArrayByArray($array,$orderArray) 
  {
    $ordered = array();
    foreach($orderArray as $key) {
    	if(array_key_exists($key,$array)) {
    		$ordered[$key] = $array[$key];
    		unset($array[$key]);
    	}
    }
    return $ordered + $array;
  }  
  
}


/**
 * CONNECTION MANAGER
 **/
class ConnectionManager
{
	private static $instance = null;
	
	private $connections = array();
	private $config = array();
	
	/**
	 * @return ConnectionManager
	 */
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new ConnectionManager;
		}
		
		return self::$instance;
	}
	
	public function __construct()
	{
		if(self::$instance !== null){
			throw new Exception(__CLASS__." is a singleton");
		}
	}
	
	private function getConfigFor($name = 'default')
	{
		return array(
		    "dsn" => "mysql:host=" . DB_HOST . ";dbname=" . DB_SCHEMA,
		    "username" => "" . DB_USERNAME . "",
		    "password" => "" . DB_PASSWORD."",
		    "driver_options" => array(
		      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
		  ));
	}
	
	/**
	 * @return PDO
	 */
	public function getConnection($name = "default")
	{
		if(!isset($this->connections[$name]))
		{
			return $this->connect($name);
		}
		
		return $this->connections[$name];
	}
	
	private function connect($name)
	{
		$conf = $this->getConfigFor($name);
		
		$conn = new PDO($conf["dsn"], $conf["username"], $conf["password"], isset($conf["driver_options"]) ? $conf["driver_options"] : array());
		
		$this->connections[$name] = $conn;
		
		return  $conn;
	}
}