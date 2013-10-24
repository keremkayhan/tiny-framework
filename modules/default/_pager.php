<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE'); ?>

<?php 
/*
 *  $sql = "SELECT count(*) as toplam FROM tablo";
    $t = Database::executeSQL($sql, true);
    $this->pages = Pagination::getInstance()->calculate_pages($t['toplam'], 100, 1); 
 * 
 */ 
 
?>

<?php if( $pages['per_page'] < $pages['total'] ): ?>

<style>
<!--
table.pager a{
	font-size: 14px;
	color: blue
}
table.pager a:hover{
	color: red;
}

table.pager a:visited{
	color: blue
}
table.pager a.active{
	color: red;
}
-->
</style>

<table class="pager" style="width: 100%">
<tr>
<td>
<?php if( $pages['current'] > 1 ): ?>
<a href="<?php print url_for(Context::getInstance()->getModuleName().'/'.Context::getInstance()->getActionName(),array('page'=> $pages['previous']),false) ?>"><?php print "Önceki" ?></a>
<?php endif; ?>
</td>
<td style="text-align: center; padding: 0 50px;">
<?php for ($i=1; $i<=$pages['last']; $i++):?>
	<a class="<?php if (Context::getInstance()->getRequest()->page == $i) print "active"?>" href="<?php print url_for(Context::getInstance()->getModuleName().'/'.Context::getInstance()->getActionName(),array('page'=>$i),false) ?>"><?php print $i?></a>
	<?php if( $i < $pages['last'] ): ?>&bull;<?php endif; ?>
<?php endfor;?>
</td>
<td>
<?php if( $pages['next'] !== $pages['last'] ): ?>
<a href="<?php print  url_for(Context::getInstance()->getModuleName().'/'.Context::getInstance()->getActionName(),array('page'=>$pages['next']),false) ?>"><?php print 'Sonraki' ?></a>
<?php endif; ?>
</td>
</tr>
</table>

<?php endif; ?>