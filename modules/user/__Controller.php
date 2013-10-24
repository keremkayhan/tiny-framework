<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>
<?php

class Controller extends BaseController
{
	
	public function login($request = null)
	{
	  
	  $field = 'email';
	  $this->field = $field;
	  
	  if( $request->isPost() ){

  	  $c = new Condition();
      $c->add($field, $request->$field);
      $c->add('password', $request->password);
      $c->add('is_active', 1);
      
  	  $user = Database::getTable('user')->findOneConditionally($c);
  	  
	    if( ! $user ){
  	    Flash::setFlash('notice', 'The password you entered is incorrect. Please try again (make sure your caps lock is off)');
  	    Flash::setFlash('email', $request->$field);
        $this->redirect('user/login');
  	    return false;
  	  }
  	  
  	  User::getInstance()->authenticate($user);
  	  
      $c = new Condition();
  	  $c->add('id', $user['id']);
      $c->add('last_login', date('Y-m-d H:i:s'));
      $c->add('login_count', $user['login_count'] + 1);
  	  Database::getTable('user')->save($c);
  	  
	  	if( $request->getParameter('remember')  ){
	      
	      $cookieHash = md5(sha1($user['email'] . $_SERVER['REMOTE_ADDR']));
        $c = new Condition();
    	  $c->add('id', $user['id']);
        $c->add('remember_me', $cookieHash);
    	  Database::getTable('user')->save($c);	      
	      
	      setcookie(slugify(PROJECT_NAME)."_remember_me", $cookieHash, time()+3600*24*365);
  	  }  
  	  
	  }
	  
		if( User::getInstance()->isAuthenticated() ){
  		if( Flash::hasFlash('ref') ){
  		  $ref = Flash::getFlash('ref');
	    	header('Location: ' . $ref);
	    	return false;
  	  }	 		  
		  
	    $this->redirect('default/index');
	  }	 

	}	
	
	
	public function register($request = null)
	{

	  if( $request->isPost() ){
	    
  	  $c = new Condition();
  	  
  	  $validate = createGuid();
  	  
      $c->add('first_name', $request->first_name);
      $c->add('last_name', $request->last_name);
      $c->add('email', $request->email);
      $c->add('password', $request->password);
      $c->add('phone', $request->phone);
      $c->add('is_active', 0);
      $c->add('validate', $validate);
      
      Database::getTable('user')->save($c);
      
	    $mailer = new Mailer();
	    
	    $subject = PROJECT_NAME . " - Hoşgeldiniz! ";
	    
	    $message = "
	    <p>Lütfen kullanıcı hesabınızı etkinleştirmek ve üyelik işleminizi tamamlamak için aşağıdaki linke tıklayınız:</p>
	    <p><a href='" . url_for('user/confirm', array("q" => $validate))."'>" . url_for('user/confirm', array("q" => $validate)). "</a></p>
	    <p>Aktivasyon linkine tıklamakta sorun yaşıyorsanız, lütfen linki kopyalayın ve İnternet tarayıcınızın adres satırına yapıştırıp ilgili sayfayı açmayı deneyin.</p>
	    <br><p>Saygılarımızla</p>
	    "; 
	    
	    $to = $request->email;
	    
	    $mailer->send($subject, $message, $to);  	  
  	  
  	  $this->redirect('user/registerAfter');
  	  
	  }
	  
	}
	
	public function confirm($request = null)
	{
	  
	  $user = Database::getTable('user')->findOneBy('validate', $request->q);
	  
	  if( !$user || $user['is_active'] ){
	    $this->redirect404();
	    return false;
	  }
	  
	  $c = new Condition();
	  $c->add('id', $user['id']);
    $c->add('last_login', date('Y-m-d H:i:s'));
    $c->add('login_count', 1);	  
	  $c->add('is_active', 1);
	  $c->add('validate', '');
	  Database::getTable('user')->save($c);
	  
	  User::getInstance()->authenticate($user);
	  
	  
	}	

	public function resetPasswordRequest($request = null)
	{
	  
	  if( $request->isPost() ){
	    $user = Database::getTable('user')->findOneBy('email', $request->email);
	    if( $user ){
	      
	      $validate = createGuid();
	      
	      $c = new Condition();
	      $c->add('id', $user['id']);
	      $c->add('validate', $validate);
	      
	      Database::getTable('user')->save($c);
	      
	      $validate = $validate;

	      $mailer = new Mailer();
  	    
  	    $subject = "Reset your password";
  	    
  	    $message = "<p>Hello ".$user['firstname']." ".$user['lastname'].", </p><p>Click on the link below to set your password</p><p><a href='" . url_for('user/resetPassword', array("q" => $validate))."'>" . url_for('login/resetPassword', array("q" => $validate)). "<a/></p><br><br><p>Regards"; 
  	    
  	    $to = $request->email;
  	    
  	    $mailer->send($subject, $message, $to);
  	    
  	    $this->redirect('user/resetPasswordRequestAfter');
	    
	    }else{
        
	      Flash::setFlash('notice', "We couldn't find you using the information you entered. Please try again.");
	      
	      $this->redirectReferer($request);
	    }
	  }
  }	
	
  public function resetPassword($request = null)
	{
	  
	  $validate = $request->q;
	  
	  $user = Database::getTable('user')->findOneBy('validate', $validate);
	  
	  if( ! $user ){
	    $this->redirect404();
	    return false;	  
	  }
	  
	  if( $request->isPost() ){
	    
	    $c = new Condition();
	    $c->add('id', $user['id']);
	    $c->add('password', $request->password);
      $c->add('last_login', date('Y-m-d H:i:s'));
      $c->add('login_count', 1);	  
      $c->add('validate', '');
      Database::getTable('user')->save($c);
      
  	  User::getInstance()->authenticate($user);      
      
      $this->redirect('user/resetPasswordAfter');
      
    }
  
	}
  
  
	public function settings($request = null)
	{
	  $this->setSecure();
	  
		if( $request->isPost() ){

  	  $c = new Condition();
      $c->add('firstname', $request->firstname);
      $c->add('lastname', $request->lastname);
      $c->add('email', $request->email);
      $c->add('password', $request->password);
      $c->add('company', $request->company);
      $c->add('address', $request->address);
      $c->add('phone', $request->phone);

      Flash::setFlash('notice', 'Your account settings have been updated.');
  	  $this->redirectReferer($request);
	  }		
	  
	}
	
	public function logout($request = null)
	{
	  User::getInstance()->signOut();
	  setcookie(slugify(PROJECT_NAME)."_remember_me", null, time()-3600);
	  session_destroy();
	  $this->redirect('default/index');
	}
	
	
	public function checkUser($request = null)
	{
		if( $request->isPost() ){
  	  $user = Database::getTable('user')->findOneBy($request->el, $request->val);
  	  if( $user ){
  	    out_STR("1");
  	  }else{
  	    out_STR("0");
  	  }
	  }		
	  die();
	}

	
	public function login_FB($request = null)
	{
	
    require 'facebook/facebook.php';
    
    $facebook = new Facebook(array(
      'appId'  => '533930023324428',
      'secret' => 'd53db33eabf5e71f7402537374f5b054',
    ));
    
    $fb_user = $facebook->getUser();
    
    if( ! $fb_user){
	    $this->redirect('default/index');
	    return false;    
    }
    
    if ($fb_user) {
      try {
        $user_profile = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
        $fb_user = null;
      }
    }
    
    $user = Database::getTable('user')->findOneBy('fb_id', $user_profile['id']);
    
    if( $user ){
      $c = new Condition();
      $c->add('id', $user['id']);
      $c->add('last_login', date('Y-m-d H:i:s'));
      $c->add('login_count', 1);    
      Database::getTable('user')->save($c);      
      User::getInstance()->authenticate($user);
	    $this->redirect('default/index');
	    return false;
    }
    
    $c = new Condition();
    $c->add('fb_id', $user_profile['id']);
    $c->add('firstname', $user_profile['first_name']);
    $c->add('lastname', $user_profile['last_name']);
    $c->add('email', $user_profile['email']);
    $c->add('last_login', date('Y-m-d H:i:s'));
    $c->add('login_count', 1);    
    $c->add('is_active', 1);
    
    $user_id = Database::getTable('user')->save($c);
    
    $user = Database::getTable('user')->find($user_id);
    
	  User::getInstance()->authenticate($user);
	  $this->redirect('default/index');      
    	
	}
	
	
	

}
