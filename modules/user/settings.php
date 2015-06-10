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
  <ol>
    <li>
      <label for="name">Name</label>
      <input type="text" id="name" name="name" class="text" style="width: 300px;"  />
    </li>

    <li style="padding-top: 10px">
      <label for="email">E-mail</label>
      <input type="text" id="email" name="email" class="text" style="width: 300px;"  />
    </li>
    
    <li class="buttons" style="clear: both; padding-top: 10px">
      <input type="submit" name="submit" value="Register" />
    </li>
  </ol>
</form>