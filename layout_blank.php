<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>
<?php header('Content-type: text/html; charset=utf-8'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo Context::getInstance()->get('title'); ?></title>
  <link rel="shortcut icon" href="<?php echo ROOT_DIRECTORY ?>favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIRECTORY ?>css/reset.css">
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIRECTORY ?>css/main.css">
  
</head>

<body>
	
	<div class="container">
    	
		<?php echo $content; ?>
    
	</div>
	
</body>
</html>