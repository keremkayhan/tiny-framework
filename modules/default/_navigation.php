<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>
<ul id="mainNav">
	<li><a href="<?php echo url_for('default'); ?>" class="active">HOMEPAGE</a></li> <!-- Use the "active" class for the active menu item  -->
	<li><a href="<?php echo url_for('user/login'); ?>">LOGIN</a></li>
	<?php if( User::getInstance()->isAuthenticated() ): ?>
	<li><a href="<?php echo url_for('user/settings'); ?>">PROFILE</a></li>
	<li class="logout"><a href="<?php echo url_for('user/logout'); ?>">LOGOUT</a></li>
	<?php endif; ?>
</ul>