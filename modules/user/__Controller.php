<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); 

class Controller extends BaseController
{
	
	public function login($request = null)
	{
	  $field = 'email';
	  $this->field = $field;
	  
	  if( $request->isPost() ){

  	  $c = new Condition();
      $c->add($field, $request->$field);
      $c->add('is_active', 1);
      if( ! USE_HASH ){
        $c->add('password', $request->password);
      }
      
  	  $user = Database::getTable('user')->findOneConditionally($c);
  	  
  	  $user_valid = true;
  	  
  	  if( USE_HASH ){
    	  if( $user ){
    	    $hash_obj = new PasswordHash( 8, false );
    	    $pass_check = $hash_obj->CheckPassword( $request->password, $user['password'] );  	  
    	  }
    	  if( ! $user || !$pass_check ){
    	    $user_valid = false;
    	  }
  	  }else{
    	  if( ! $user ){
    	    $user_valid = false;
    	  }
  	  }
  	  
  	  if( ! $user_valid ){
  	    Flash::setFlash('notice', 'E-posta adresi veya şifre hatalı.');
        $this->redirect('user/login');
        return false;
  	  }
  	  
  	  $c = new Condition();
  	  $c->add('id', $user['id']);
      $c->add('last_login', date('Y-m-d H:i:s'));
      $c->add('login_count', $user['login_count'] + 1);
      
      Database::getTable('user')->save($c);
      
      User::getInstance()->authenticate($user);
      
	  	if( $request->getParameter('remember')  ){
	      $cookieHash = $this->hash($user['email'] . $_SERVER['REMOTE_ADDR']);
        $c = new Condition();
    	  $c->add('id', $user['id']);
        $c->add('remember_me', $cookieHash);
    	  Database::getTable('user')->save($c);	      
	      
        $cookie_params = session_get_cookie_params();
	      setcookie(slugify(PROJECT_NAME)."_remember_me", $cookieHash, time()+3600*24*365, $cookie_params['path'], $cookie_params['domain'], true, true);
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
      $c->add('password', $this->hash($request->password));
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
	  
	  $this->redirect('user/confirmAfter');
	  
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
  	    
  	    $subject = PROJECT_NAME . "Şifre yenileme";
  	    
  	    $message = "<p>Sayın ".$user['name'].", </p>
  	    <p>Şifrenizi yeniden oluşturmak için lütfen aşağıdaki linke tıklayın</p>
  	    <p><a href='" . url_for('user/resetPassword', array("q" => $validate))."'>" . url_for('login/resetPassword', array("q" => $validate)). "</a></p>
	    	<p>Linke tıklamakta sorun yaşıyorsanız, lütfen linki kopyalayın ve İnternet tarayıcınızın adres satırına yapıştırıp ilgili sayfayı açmayı deneyin.</p>
	   		<br><p>Saygılarımızla</p>";   	    
  	       
  	    $to = $request->email;
  	    
  	    $mailer->send($subject, $message, $to);
  	    
  	    $this->redirect('user/resetPasswordRequestAfter');
	    
	    }else{
        
	      Flash::setFlash('notice', "Girdiğiniz e-posta adresi sistemimizde kayıtlı değil.");
	      
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
      if( USE_HASH ){ $request->password = $this->hash($request->password); }
      $c->add('password', $request->password);
	    $c->add('last_login', date('Y-m-d H:i:s'));
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
      $c->add('company', $request->company);
      $c->add('address', $request->address);
      $c->add('phone', $request->phone);

      Flash::setFlash('notice', 'Kullanıcı bilgileriniz güncellendi.');
  	  $this->redirectReferer($request);
	  }		
	}
	
	public function logout($request = null)
	{
	  User::getInstance()->signOut();
    $cookie_params = session_get_cookie_params();
	  setcookie(slugify(PROJECT_NAME)."_remember_me", null, time()-3600, $cookie_params['path'], $cookie_params['domain'], true, true);
	  session_destroy();
	  $this->redirect('default/index');
	}
	
	
	public function checkUser($request = null)
	{
		if( $request->isPost() ){
  	  $user = Database::getTable('user')->findOneBy($request->el, $request->val);
  	  if( $user ){
  	    out_STR("1");
    	  if( User::getInstance()->isAuthenticated() ){
  		    if( User::getInstance()->email == $request->val){
  		      out_STR("0");
  		    }
  		  }  	    
  	  }else{
  	    out_STR("0");
  	  }
	  }		
	  die();
	}
	
  private function hash($password)
  {
    $hash_obj = new PasswordHash( 8, false );
    $hash = $hash_obj->HashPassword( $password );
    if ( strlen( $hash ) < 20 ){
      $hash = $this->hash($password);
    }
    return $hash;        
  }	

  public function hashAll($request = null)
  {  
    $sql = "SELECT * FROM user WHERE password IS NOT NULL";
    $users = Database::executeSQL($sql);
    
    foreach ( $users as $user ){
      $hashed_pass = $this->hash($user['password']);
      echo $hashed_pass;
      echo "<hr>";
      
      $sql = "UPDATE user SET password = '".$hashed_pass."' WHERE id = ? LIMIT 1 ";
      //Database::executeSQL($sql, array($user['id']));
    }
  }

  public function encryptAll($request = null)
  {
    
    $field = "email";

    $sql = "SELECT * FROM user WHERE ".$field." IS NOT NULL";
    $users = Database::executeSQL($sql);

    foreach ( $users as $user ){
      $sql = "UPDATE user SET ".$field." = '".encrypt($user[$field])."' WHERE id = ? LIMIT 1 ";
      //Database::executeSQL($sql, array($user['id']));
    }
  }  
  
  public function decryptAll($request = null)
  {
    
    $field = "email";

    $sql = "SELECT * FROM user WHERE ".$field." IS NOT NULL";
    $users = Database::executeSQL($sql);

    foreach ( $users as $user ){
      $sql = "UPDATE user SET ".$field." = '".decrypt($user[$field])."' WHERE id = ? LIMIT 1 ";
      //Database::executeSQL($sql, array($user['id']));
    }
  }  
  
}