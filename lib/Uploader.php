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

require_once 'vendor/class.upload.php';

class Uploader 
{
  private $maxWidth = 500;
	private $maxThumbWidth = 50;
	public $filename = "";
	public $directory = "uploads";
		
	public function __construct($maxWidth = NULL, $maxThumbWidth = NULL)
	{
		if( $maxWidth ){ $this->maxWidth = $maxWidth; }
		if( $maxThumbWidth ){ $this->maxThumbWidth = $maxThumbWidth; }
	}
	
	public function upload($image)
	{
		
		$filename = $image['name'] ;
    $file_ext = strrchr($filename, '.');
    $file_body = date('YmdHis');
		$file_full_name = $file_body."".$file_ext;
    $handle = new Upload($image);
    if ($handle->uploaded) {

      
      $handle->file_overwrite     = true;
      $handle->file_new_name_body = $file_body;

      // RESIZE FOR IMAGE
			list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);
      
      if ( $width > $this->maxWidth ) {
      	
          $handle->image_resize       = true;
          $handle->image_x            = $this->maxWidth;
          $handle->image_ratio_y      = true;
      }
      $handle->process($this->directory);
      if ($handle->processed) {
      } else {
      }

      $orig_image = $this->directory . DIRECTORY_SEPARATOR . $file_body.$file_ext;
      list($width, $height, $type, $attr) = getimagesize($orig_image);

      // RESIZE FOR THUMBNAIL
      
      $handle->file_overwrite     = false;
      $handle->file_new_name_body = $file_body."_thumb";
      list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);
      
      if ( $width > $this->maxThumbWidth ) {
      	
          $handle->image_resize       = true;
          $handle->image_x            = $this->maxThumbWidth;
          $handle->image_ratio_y      = true;
      }        
      
      $handle->process($this->directory);
      
      $this->filename = $file_body.$file_ext;
      return $handle->processed; 

    }
		return false;
	}	
	
	public static function unlink($filename, $directory = 'uploads')
	{	
	  $file = explode('.', $filename);
		unlink($directory . DIRECTORY_SEPARATOR . $file[0] . '.' . $file[1]);
		unlink($directory . DIRECTORY_SEPARATOR .  $file[0] . '_thumb.' . $file[1]);
	}
	
	public static function getThumbnail($file_name)
	{	
    $body = substr($file_name, 0, strpos($file_name, strrchr($file_name,'.')));
    $ext = strrchr($file_name,'.');	  
		return $body."_thumb".$ext;
	}	
	
}

