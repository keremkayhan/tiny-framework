<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>
<?php header('Content-type: text/html; charset=utf-8'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo PROJECT_NAME ?></title>
  <link rel="shortcut icon" href="<?php echo ROOT_DIRECTORY ?>favicon.ico" />
  <script type="text/javascript" src="<?php echo ROOT_DIRECTORY ?>js/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="<?php echo ROOT_DIRECTORY ?>js/jquery.validate.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIRECTORY ?>css/reset.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIRECTORY ?>css/main.css">
	
	<script type="text/javascript" src="<?php echo ROOT_DIRECTORY ?>js/fancybox/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIRECTORY ?>js/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
  
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIRECTORY ?>js/apprise/apprise.min.css"/>
	<script type="text/javascript" src="<?php echo ROOT_DIRECTORY ?>js/apprise/apprise-1.5.min.js"></script>	

<body>

	<div id="wrapper">
		<?php include_partial('default/header') ?>
		<?php include_partial('default/navigation')?>
    	
		<?php echo $content; ?>
    
    <?php include_partial('default/footer')?>
	</div>
	
</body>
</html>