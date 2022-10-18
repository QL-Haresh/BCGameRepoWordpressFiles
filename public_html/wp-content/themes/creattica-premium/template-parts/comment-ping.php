<li <?php hybridextend_attr( 'comment' ); ?>>

	<header class="comment-meta comment-ping">
		<cite <?php hybridextend_attr( 'comment-author' ); ?>><?php comment_author_link(); ?></cite>
		<br />
		<div class="comment-meta-block">
			<time <?php hybridextend_attr( 'comment-published' ); ?>><?php $d = apply_filters( 'comment_date_format', '' ); echo get_comment_date( $d ); ?></time>
		</div>
		<div class="comment-meta-block">
			<a <?php hybridextend_attr( 'comment-permalink' ); ?>><?php _e( 'Permalink', 'creattica-premium' ); ?></a>
		</div>
		<?php edit_comment_link(); ?>
	</header><!-- .comment-meta -->

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>