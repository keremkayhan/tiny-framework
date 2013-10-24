<?php
$request = Context::getInstance()->getRequest();

$sql = "SHOW TABLES";
$tables = Database::executeSQL($sql);
$table_name = $request->table;
if( $table_name && $request->action == 'new'){
  
  $tempAction = file_get_contents("modules/backend/generate_action.php");
  $tempView = file_get_contents("modules/backend/generate_view.php");
  $tempAction = str_replace('{table_name}', $table_name, $tempAction);
  $tempView = str_replace('{table_name}', $table_name, $tempView);
  
  $viewFile = "modules/backend/" . $table_name . ".php";
  file_put_contents($viewFile, $tempView);
  
  $controllerFile = "modules/backend/__Controller.php";
  $controller = file_get_contents($controllerFile);
  $controller = str_replace('/* GENERATED ACTIONS */', '/* GENERATED ACTIONS */'.$tempAction, $controller);
  file_put_contents($controllerFile, $controller);
}

?>

<div class="content">
	<ul>
	<?php foreach ($tables as $table): ?>
	<li>
	  <?php echo $table['Tables_in_'.DB_SCHEMA]; ?>
	  <a href="<?php echo url_for('backend/generate', array('action' => 'new', 'table' => $table['Tables_in_'.DB_SCHEMA])); ?>">Generate</a>
	</li>
	<?php endforeach; ?>
	</ul>
</div>