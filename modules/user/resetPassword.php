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

<form method="post" id="loginform">
  <ul>
    <li>
      <label for="password">Şifre</label>
      <input type="password" id="password" name="password" class="text required" style="width: 300px;" value="" maxlength="155"/>
    </li>
    <li>
      <label for="confirm_password">Şifre (tekrar)</label>
      <input type="password" id="password2" name="password2" equalTo="#password"  class="text required" style="width: 300px;" value="" maxlength="155" />
    </li>    
  		<li>
        <label></label>
        <?php include_partial('default/notice'); ?>
      </li>                
    <li class="buttons">
			<input type="submit" name="submit" id="submitButton" value="Gönder" />
      <span id="waitWarn" style="color: red; display: none;">Lütfen bekleyiniz...</span>      
    </li>

    
  </ul>
</form>

<script type="text/javascript">
$(document).ready(function() {
	$("#password").focus();

	$("#loginform").validate({
		rules: {
			password: {
				required: true,
				minlength: 6
			}
		},
	  submitHandler: function(form) {
      form.submit();
      $('#submitButton').hide();
      $('#waitWarn').show();
    }
	});
	


});
</script>