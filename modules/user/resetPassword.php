<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>

<?php if( Flash::hasFlash('username') ): ?>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$('#username').val('<?php echo Flash::getFlash('username') ?>');	
})
//-->
</script>
<?php endif; ?>

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
      <input type="password" id="password" name="password" class="text" style="width: 300px;" value="" />
    </li>
    <li>
      <label for="confirm_password">Şifre (tekrar)</label>
      <input type="password" id="confirm_password" name="confirm_password" class="text" style="width: 300px;" value="" />
    </li>    
  		<li>
        <label></label>
        <?php include_partial('default/notice'); ?>
      </li>                
    <li class="buttons">
      <input type="submit" name="submit" id="submit" value="Gönder" />
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
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			}
		},
		messages: {
			password: {
				required: "Lütfen şifrenizi girin.",
				minlength: "Şifreniz en az 5 karakter uzunluğunda olmalı."
			},
			confirm_password: {
				required: "Lütfen şifrenizi girin.",
				minlength: "Şifreniz en az 5 karakter uzunluğunda olmalı.",
				equalTo: "İki şifre birbiri ile aynı olmalı."
			}
		}
	});
	


});
</script>