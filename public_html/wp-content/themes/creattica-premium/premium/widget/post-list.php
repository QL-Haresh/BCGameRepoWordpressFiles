<?php
/* Create Query */
$count = intval( $count );
$query_args = array();
$query_args['posts_per_page'] = ( empty( $count ) ) ? 3 : $count;
if ( $category )
	$query_args['category'] = $category;
$query_args = apply_filters( 'hoot_post_list_query', $query_args, $instance, $before_title, $title, $after_title );
$postlist = get_posts( $query_args );
global $post;

/* Display List */
if ( !empty( $postlist ) ) : ?>

	<div class="post-list-widget">

		<?php
		/* Display Title */
		if ( $title )
			echo wp_kses_post( apply_filters( 'hoot_widget_title', $before_title . $title . $after_title, 'post-list', $title, $before_title, $after_title ) );
		?>

		<?php foreach ( $postlist as $post ) : setup_postdata( $post ); ?>

			<?php $thumbnail = ( !$hide_thumbnails && has_post_thumbnail() ) ? true : false; ?>
			<div class="post-list-post<?php if ( !$thumbnail ) echo ' no-thumb'; ?>">

				<?php if ( $thumbnail ) : ?>
					<div class="post-list-thumb">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
					</div>
				<?php endif; ?>

				<div class="post-list-content">
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<?php if ( !$hide_date || !$hide_comments ) : ?>
						<div class="post-list-meta">
							<?php
							if ( !$hide_date ) { echo get_the_date(); }
							if ( !$hide_date && !$hide_comments ) _e( ' / ', 'creattica-premium' );
							if ( !$hide_comments ) {
								comments_popup_link(	__( '0 Comments', 'creattica-premium' ),
														__( '1 Comment', 'creattica-premium' ),
														__( '% Comments', 'creattica-premium' ), 'comments-link', '' );
								}
							?>
						</div>
					<?php endif; ?>
				</div>

				<div class="clearfix"></div>
			</div><!-- .post-list-post -->

		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>

	</div>

<?php endif; ?>