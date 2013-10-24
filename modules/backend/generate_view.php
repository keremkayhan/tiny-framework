<style>
<!--
table.admin_list td{
  border: 1px solid; padding: 6px
}
-->
</style>

<div class="content">
	<p>
	<a href="<?php echo url_for('backend/{table_name}', array('action' => 'new')); ?>">Yeni</a>
	</p>
	
  <?php if( $request->action && $request->action == 'edit' ): ?>
	<form method="post">
		<input type="hidden" name="action" value="edit" />
		<input type="hidden" name="{table_name}[id]" value="<?php echo $request->id ?>" />
		<ul>
			<?php foreach ($fields as $field): ?>
			<li>
				<label for="<?php echo $field ?>"><?php echo $field ?></label>
				<input type="text" name="{table_name}[<?php echo $field ?>]" value="<?php echo $record[$field] ?>" id="<?php echo $field ?>" />
			</li>
			<?php endforeach; ?>
			<li>
			<input type="submit" value="Gönder" />
			</li>
		</ul>
	</form>
	<?php endif; ?>
	
  <?php if( $request->action && $request->action == 'new' ): ?>
	<form method="post">
		<input type="hidden" name="action" value="new" />
		<ul>
			<?php foreach ($fields as $field): ?>
			<li>
				<label for="<?php echo $field ?>"><?php echo $field ?></label>
				<input type="text" name="{table_name}[<?php echo $field ?>]" id="<?php echo $field ?>" />
			</li>
			<?php endforeach; ?>
			<li>
			<input type="submit" value="Gönder" />
			</li>
		</ul>
	</form>
	<?php endif; ?>	

  <table class="admin_list">
    <tr>
      <?php foreach ($fields as $field): ?>
      <td>
	      <?php echo $field; ?>
      </td>
      <?php endforeach; ?>
  	  <td colspan="2">İşlem</td>
    </tr>
    
    <?php foreach ($items as $item): ?>
    <tr>
      <?php foreach ($fields as $field): ?>
      <td>
	      <?php echo $item[$field]; ?>
      </td>
      <?php endforeach; ?>
      <td><a href="<?php echo url_for('backend/{table_name}', array('action' => 'edit', 'id' => $item['id'])); ?>">Güncelle</a></td>
      <td><a href="<?php echo url_for('backend/{table_name}', array('action' => 'delete', 'id' => $item['id'])); ?>" onclick="return confirm('Emin misiniz?')">Sil</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>