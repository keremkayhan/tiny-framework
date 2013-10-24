<?php if( Flash::hasFlash('notice') ): ?>
  <p id="notice" class="attention"><?php echo Flash::getFlash('notice') ?></p>
  	<script type="text/javascript">
		<!--
			setInterval(function () {$('p#notice').slideUp('fast');}, 3000);
		//-->
		</script>
<?php endif; ?>  