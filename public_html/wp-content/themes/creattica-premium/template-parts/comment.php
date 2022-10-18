<li <?php hybridextend_attr( 'comment' ); ?>>

	<article>
		<header class="comment-avatar">
			<?php echo get_avatar( $comment ); ?>
			<?php global $post;
			if ( $comment->user_id === $post->post_author ) { ?>
				<div class="comment-by-author"><?php _e( 'Author', 'creattica-premium' ); ?></div>
			<?php } ?>
		</header><!-- .comment-avatar -->

		<div class="comment-content-wrap">

			<div <?php hybridextend_attr( 'comment-content' ); ?>>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<footer class="comment-meta">
				<div class="comment-meta-block">
					<cite <?php hybridextend_attr( 'comment-author' ); ?>><?php comment_author_link(); ?></cite>
				</div>
				<div class="comment-meta-block">
					<time <?php hybridextend_attr( 'comment-published' ); ?>><?php $d = apply_filters( 'comment_date_format', '' ); echo get_comment_date( $d ); ?></time>
				</div>
				<div class="comment-meta-block">
					<a <?php hybridextend_attr( 'comment-permalink' ); ?>><?php _e( 'Permalink', 'creattica-premium' ); ?></a>
				</div>
				<?php if ( comments_open() ) : ?>
					<div class="comment-meta-block">
						<?php hybrid_comment_reply_link(); ?>
					</div>
				<?php endif; ?>
				<?php edit_comment_link(); ?>
			</footer><!-- .comment-meta -->

		</div><!-- .comment-content-wrap -->

	</article>

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>