<article <?php hybridextend_attr( 'post' ); ?>>

	<?php if ( apply_filters ( 'hoot_display_404_content_title', true ) ) : ?>
		<header class="entry-header">
			<?php
			global $hoot_theme;
			$tag = ( !empty( $hoot_theme->loop_meta_displayed ) ) ? 'h2' : 'h1';
			echo "<{$tag} class='entry-title'>" . __( 'Nothing found', 'creattica-premium' ) . "</{$tag}>";
			?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<div <?php hybridextend_attr( 'entry-content', '', 'no-shadow' ); ?>>
		<div class="entry-the-content">
			<?php do_action( 'hoot_404_content' ); ?>
		</div>
	</div><!-- .entry-content -->

</article><!-- .entry -->