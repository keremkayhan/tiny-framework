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

require_once 'vendor/class.phpmailer.php';

/* SMTP SETTINGS */
define('MAILER_HOST', 'smtp.example.com');
define('MAILER_SMTP_DEBUG', 1);
define('MAILER_PORT', 587);

/* SMTP AUTHENTICATION */
define('MAILER_USERNAME', 'info@example.com');
define('MAILER_PASSWORD', 'password');

/* FROM PARTS */
define('MAILER_FROM', 'info@example.com');
define('MAILER_FROM_NAME', PROJECT_NAME);

/* HTML TEMPLATES */
define('MAILER_IS_HTML', 1); // Set to 0 if you want to send message as text...
define('MAILER_TEMPLATE', 'mail_template.php'); // Set to 0 if you don't want to use the template...

class Mailer 
{
	
	private $mail;
	
	public function __construct()
	{
		$this->mail 						= new PHPMailer();
		$this->mail->Host 			= MAILER_HOST;
		$this->mail->SMTPDebug	= MAILER_SMTP_DEBUG;
		$this->mail->Port 			= MAILER_PORT;
		$this->mail->Mailer 		= "smtp";
		$this->mail->SMTPAuth 	= "true";
		$this->mail->Username 	= MAILER_USERNAME;
		$this->mail->Password 	= MAILER_PASSWORD;
		$this->mail->IsHTML(MAILER_IS_HTML);
		$this->mail->CharSet		= "UTF-8";
	}	
	
	public function send($subject, $message, $to, $printBody = false, $debug = false, $from = NULL, $fromname = NULL)
	{
		
		if( empty($to) ){
			throw new Exception("To email must be specified", 500);
			return false;
		}
		
		if( MAILER_TEMPLATE ){
			$body = $this->getMailTemplate($message);
		}else{
			$body = $message;
		}
		
		$this->mail->From			= ($from ? $from : MAILER_FROM);
		$this->mail->FromName	= ($fromname ? $fromname : MAILER_FROM_NAME);
		$this->mail->Subject  = $subject;
		$this->mail->Body 		= $body;
		
		if(is_array($to)):
			foreach ($to as $m):
				$this->mail->AddAddress($m);
			endforeach;
		else:
			$this->mail->AddAddress($to);
		endif;		
		
		$retVal = '';
		
		if($this->mail->Send()) {
			$retVal = true;
			if( $printBody ){
			 $retVal = $body;
			}
		} else {
			$retVal = false;
		  if( $debug ){
			 $retVal = 'Mail error: '.$mail->ErrorInfo;
			}			
		}

		return $retVal;
	
	}	
	
  public function getMailBody($message)
  {
    if( MAILER_TEMPLATE ){
      $body = $this->getMailTemplate($message);
    }else{
      $body = $message;
    }
    return $body;
  } 	
	
	
	public function getMailTemplate($message = null)
	{
		//$message = nl2br($message);
		if(!is_null($message))
		{
			ob_start();
			require MAILER_TEMPLATE;
			$mailTemplate = str_replace("{%message%}", $message, ob_get_contents());
			ob_end_clean();		
		}
		
		return $mailTemplate;
	}	
	
}

