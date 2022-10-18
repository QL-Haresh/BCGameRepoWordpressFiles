<?php
global $hoot_theme;

/* Reset any previous custom-blogposts */
$hoot_theme->blogposts = array();

/* Set Query Values */
$count = intval( $count );
$hoot_theme->blogposts['args']['posts_per_page'] = ( empty( $count ) ) ? 3 : $count;
if ( $category )
	$hoot_theme->blogposts['args']['cat'] = $category;

/* Set Extra Values */
$hoot_theme->blogposts['title'] = !empty( $title ) ? $title : ''; // $before_title . $title . $after_title
$hoot_theme->blogposts['pagination'] = false;
$hoot_theme->blogposts['layout'] = apply_filters( 'hoot_blog_widget_sidebar', 'none' ); // optional: full-width

/* Add Post Content to 'hoot_blogposts_pagination' at priority > 10 to come after pagination (although pagination is set to false for now in the blog widget) */
if ( !empty( $post_content ) ) {
	$hoot_theme->blogposts['post_content'] = $post_content;
	add_action( 'hoot_blogposts_content_end', 'hoot_blog_widget_postcontent', 15 );
}
if ( !function_exists( 'hoot_blog_widget_postcontent' ) ) {
	function hoot_blog_widget_postcontent(){
		global $hoot_theme;
		echo '<div class="blog-widget-post-content">' . $hoot_theme->blogposts['post_content'] . '</div>';
	}
}

/* Allow for changing of query_args */
do_action( 'hoot_blog_widget_start' );

/* Display Custom Posts Template */
?><div class="blog-widget<?php if ( empty( $post_content ) ) echo ' no-post-footer'; ?>"><?php
	get_template_part( 'premium/template-parts/custom-blogposts' );
?></div><?php

/* Remove previously added action */
remove_action( 'hoot_blogposts_content_end', 'hoot_blog_widget_postcontent', 15 );