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
      <label for="email">E-posta</label>
      <input type="text" id="email" name="email" class="text" style="width: 300px;" value="" />
    </li>
    <li class="buttons">
      <input type="submit" name="submit" id="submit" value="Gönder" />
    </li>

    
  </ul>
</form>

<script type="text/javascript">
$(document).ready(function() {
	$("#email").focus();
});
</script>