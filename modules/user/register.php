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
  padding: 5px 0;
}

#loginform ul li label{
  clear: both;
  float: left;
}
-->
</style>  
    
<form method="post" id="loginform">
  <ul>
    <li>
      <label for="first_name">First Name</label>
      <input type="text" id="first_name" name="first_name" class="text required" style="width: 300px;" maxlength="100" />
    </li>
    <li>
      <label for="last_name">Last Name</label>
      <input type="text" id="last_name" name="last_name" class="text required" style="width: 300px;"  maxlength="100" />
    </li>    
    <li style="padding-top: 10px">
      <label for="email">E-mail</label>
      <input type="text" id="email" name="email" class="text required email check" style="width: 300px;"  maxlength="100" />
    </li>
    <li style="padding-top: 10px">
      <label for="username">Username</label>
      <input type="username" id="username" name="username" class="text required" style="width: 300px;" minlength="2"  maxlength="100" />
    </li>
    <li style="padding-top: 10px">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" class="text required" style="width: 300px;"  maxlength="100" />
    </li>
    <li style="padding-top: 10px">
      <label for="password2">Password Again</label>
      <input type="password" id="password2" name="password2" equalTo="#password" class="text required" style="width: 300px;"  maxlength="100" />
    </li>
    
    <li class="buttons" style="clear: both; padding-top: 10px">
      <input type="submit" id="submitButton" name="submit" value="Register" />
      <span id="waitWarn" style="color: red; display: none;">Lütfen bekleyiniz...</span>
    </li>
  </ul>
</form>

<script type="text/javascript">

$.validator.addMethod(
    'check', 
    function (value, element) {
  		$.ajax({
  			type: "POST",
  		  url: "<?php echo url_for('user/checkUser') ?>/t=<?php echo $_SERVER['REQUEST_TIME']; ?>",
  		  data: {el:element.name,val:value},
  		  async: false,
  			success: function(respond){
  				isValid = true;
    			if( respond == 1 ){
    				isValid = false;
      		}
  			}
  		});
  		return isValid;
    }, 
    'Bu e-posta adresi alınmış.'
);

$(document).ready(function() {
	$("#registerform").validate({
	  rules: {
	    password: {
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