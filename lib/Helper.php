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

function cleanUpForSQL($str)
{
	$str = trim($str);
	$str = addslashes($str);
	$str = strip_tags($str);
	return $str;
}
	
function cleanUpForHTML($str, $nl_br = false)
{
  $str = stripslashes($str);
  $str = str_replace("\\", "", $str);
	$str = str_replace("&#039;", "'", $str);
	$str = str_replace("&amp;", "&", $str);
	$str = str_replace("<", "&lt;", $str);
	$str = str_replace(">", "&gt;", $str);
	if( $nl_br ){
	  $str = nl2br($str);
	}
	
	return $str;
}

function strtolowerTurkish($str){
  $low = array("Ü" => "ü", "Ö" => "ö", "Ğ" => "ğ", "Ş" => "ş", "Ç" => "ç", "İ" => "i", "I" => "ı");
  return strtolower(strtr($str, $low));  
}

function cleanuserinput($dirty){
  if (get_magic_quotes_gpc()) {
      $clean = mysql_real_escape_string(stripslashes($dirty));
  }else{
      $clean = mysql_real_escape_string($dirty);
  }
  return $clean;
}

function slugify($str, $seperator = null)
{
	if(is_null($seperator) || !is_string($seperator))
	{
		$seperator = "-";
	}
	$nonEnUpperCase = array("Ğ", "Ü", "Ş", "İ", "Ö", "Ç", "I");
	$nonEnLowerCase = array("ğ", "ü", "ş", "i", "ö", "ç", "ı");
	$enUpperCase = array("G", "U", "S", "I", "O", "C", "I");
	$enLowerCase = array("g", "u", "s", "i", "o", "c", "i");		

	$str = str_replace($nonEnLowerCase, $enLowerCase, $str);
	$str = str_replace($nonEnUpperCase, $enUpperCase, $str);
	$str = strtolower($str);
	
	$str = preg_replace("#[^a-zA-Z0-9 ]#", "", $str);
	$str = preg_replace("# +#", $seperator, $str);
	return $str;
}
	
function time_ago($timestamp)
{
  $difference = time() - $timestamp;
  $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
  $lengths = array("60","60","24","7","4.35","12","10");
  
  if ($difference > 0) { // this was in the past time
  $ending = "ago";
  } else { // this was in the future time
  $difference = -$difference;
  $ending = "to go";
  }
  for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
  $difference = round($difference);
  if($difference != 1) $periods[$j].= "s";
  $text = "$difference $periods[$j] $ending";
  return $text;
}

	
function getCurrentURL($hostOnly = null) 
{
  $pageURL = 'http://'.$_SERVER["SERVER_NAME"];
  
  if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= ":".$_SERVER["SERVER_PORT"];
  } 
  
  if( !$hostOnly ){
    $pageURL .= $_SERVER["REQUEST_URI"];
  }
  
  return $pageURL;
}

function getCurrentURI() 
{
  return $_SERVER['REQUEST_URI'];
}

	
function formatDateTime($tarih, $onlyDate = false)
{
	$ret = "";
	if( $tarih ){
		$ret = substr($tarih,8,2).".".substr($tarih,5,2).".".substr($tarih,0,4)." - ".substr($tarih,10);
		if( $onlyDate ){
			$ret = substr($tarih,8,2).".".substr($tarih,5,2).".".substr($tarih,0,4);
		}
	}
	return $ret;			
}

function include_partial($partial = null, $array = null) 
{
  $partial = explode('/', $partial);
  
	$require = 'modules/'.$partial[0].'/_'.$partial[1].'.php';
	
	if( isset($array) ){
	
		foreach ($array as $key => $value) {
			$$key = $value;
		}
	}
	
	require $require;
}
  


function url_for($module_action = null, $params = null)
{
	$url = WEB_ROOT . $module_action;
	
	if( $params ){
	  $qs = http_build_query($params);
	  
	  $url .= "/" . $qs;
	}
	
	return $url;  
}

function createGuid()  
{
  $guid = "";
  for ($i = 0; ($i < 8); $i++) {
    $guid .= sprintf("%02x", mt_rand(0, 255));
  }
  return $guid;
}

function url_to_link($text)
{
  $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
  if (preg_match_all($reg_exUrl, $text, $url)) {
    foreach($url[0] as $v){
      $position = strpos($text,' '.$v)+1;
      $text = substr_replace($text,'', $position, strlen($v));
      $text = substr_replace($text,''.$v.'', $position ,0);
    }
    return $text;
  }
  else {
    return $text;
  }
}

function strGetBetween($content,$start,$end)
{
  $r = explode($start, $content);
  if (isset($r[1])){
      $r = explode($end, $r[1]);
      return $r[0];
  }
  return '';
}

function generate_description($str, $html = true, $len = 25 )
{
  if( $html ){ $str = strip_tags($str); }
  
  $arr = explode(' ', $str);
  $cnt = count($arr);
  $arr = array_slice($arr, 0, $len);
  $ret = implode(' ', $arr);
  
  if( $cnt > $len ){ $ret .= "..."; }
  
  return $ret;
}

function get_tomorrow_date() {
  $tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
  return date("Y-m-d", $tomorrow);
}

function is_localhost() {
 $localhost_ids = array('localhost', '127.0.0.1');
 if(in_array($_SERVER['HTTP_HOST'],$localhost_ids)){
    // not valid
    return 1;
 }
}

function removeSpacialCharacters($string="") 
{
  if (preg_match('/[^\w\d_ -]/si', $string)) {
   return preg_replace('/[^a-zA-Z0-9_ -]/s', '', $string);
  } else {
   return preg_replace('/\s/', ' ', $string);
  }
}

function validateEmail($email)
{
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    return true;
  }else{
    return false;
  }
}

function downloadFile($file)
{
  $file_name = $file;
  $mime = 'application/force-download';
	header('Pragma: public'); 	// required
	header('Expires: 0');		// no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private',false);
	header('Content-Type: '.$mime);
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Content-Transfer-Encoding: binary');
	header('Connection: close');
	readfile($file_name);		// push it out
	exit();
}

function highlight($sString, $aWords) {
	if (!is_array ($aWords) || empty ($aWords) || !is_string ($sString)) {
		return false;
	}

	$sWords = implode ('|', $aWords);
 	return preg_replace ('@\b('.$sWords.')\b@si', '<strong style="background-color:yellow">$1</strong>', $sString);
}

function get_human_file_size($size)
{
	$units = array('Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB');
	return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$units[$i];
}

function time_diff($from, $to) 
{
 $dStart = new DateTime(date($from));
 $dEnd  = new DateTime(date($to));
 $dDiff = $dStart->diff($dEnd);
  return $dDiff->days;
}

function list_files($dir)
{
  if(is_dir($dir)){
    if($handle = opendir($dir)){
      while(($file = readdir($handle)) !== false)
      {
        if($file != "." && $file != ".." && $file != "Thumbs.db"/*pesky windows, images..*/){
          echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";
        }
      }
      closedir($handle);
    }
  }
}

function is_iOS() 
{
  $isIOS = false;
 
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') ||
  	strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') ||
  	strpos($_SERVER['HTTP_USER_AGENT'], 'iPod')) {
  	$isIOS = true;
  }
   return $isIOS;
}

function out_XML($items, $fields) 
{
  header ("Content-Type:text/xml");
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  echo "<tree>\n";
  foreach ($items as $item){
    echo "<item>";
    foreach ($fields as $field){
      echo "<".$field."><![CDATA[".$item[$field]."]]></".$field.">";
    }
    echo "</item>";
  }
  echo "</tree>";  
}

function out_JSON($items) 
{
  header('Cache-Control: no-cache, must-revalidate' );
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  header("Content-type: application/json; charset=utf-8");

  $out = json_encode($items);
  echo $out;  
}


function out_STR($string) 
{
  header_UTF8();
  $out = $string;
  echo $out;  
}

function header_UTF8() 
{
  header('Content-type: text/html; charset=utf-8');
}

function dd($mixed) 
{
  header_UTF8();
  echo "<pre>";
  var_dump($mixed);
  echo "</pre>";
  die("--");
}

function pp($array) 
{
  header_UTF8();
  echo "<pre>";
  print_r($array);
  echo "</pre>";
  die();
    
}

function checkParameters(Request $request, $array) 
{
  foreach ($array as $item){
    if( empty($request->$item) ){
      out_STR("Parameter missing: <b>" . $item . "</b>");
      die();
    }
  }
}

function checkPost(Request $request) 
{
  if( ! $request->isPost()){
    out_STR("Method must be: <b>POST</b>");
    die();  
  }
}

