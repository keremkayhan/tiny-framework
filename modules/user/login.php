<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>

<style>
<!--
#loginform{
  border: 1px solid; 
  width: 320px; 
  margin: 0 auto; 
  background-color: #F7F7F7; 
  border: 1px solid #CCCCCC; 
  padding: 30px;
  margin-top: 60px;
  
}

#loginform ul li {
  overflow: hidden;
  clear: both;
  padding: 5px 0;
}
-->
</style>

<form action="<?php echo url_for('user/login') ?>" method="post" id="loginform">
  <ul>
    <li>
      <label for="<?php echo $field ?>">E-posta</label>
      <input type="text" id="<?php echo $field ?>" name="<?php echo $field ?>" class="text" style="width: 300px;" value="" />
    </li>
    <li>
      <label for="password">Şifre</label>
      <input type="password" name="password" class="text" style="width: 300px;" value="" />
    </li>
    <li>
    	<input type="checkbox" id="remember" name="remember" class="text" style="width: 13px; height: 13px; margin-top: 2px; float: left; border: 0 none;" value="1" />
      <label for="remember" style="float: left; margin: 0; padding-top: 3px; ">Beni hatırla</label>
    </li>
    <li class="buttons">
      <input class="button-submit" type="submit" name="submit" id="submit" value="Giriş" />
    </li>
		<li>
      <label></label>
      <?php include_partial('default/notice'); ?>
    </li>                
  </ul>
</form>