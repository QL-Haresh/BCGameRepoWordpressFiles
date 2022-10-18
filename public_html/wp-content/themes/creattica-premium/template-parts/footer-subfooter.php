<?php
if ( ! is_active_sidebar( 'hoot-sub-footer' ) )
	return;
?>
<div <?php hybridextend_attr( 'sub-footer', '', 'hgrid-stretch inline-nav enforce-typo' ); ?>>
	<div class="hgrid">
		<div class="hgrid-span-12">
			<?php dynamic_sidebar( 'hoot-sub-footer' ); ?>
		</div>
	</div>
</div>