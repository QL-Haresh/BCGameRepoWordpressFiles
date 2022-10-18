<?php
/**
 * Helper functions file for extending the framework. Functions defined here are generally used across
 * the entire theme to make various tasks faster.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* == utility == */

/**
 * Trim a string like PHP trim function
 * It additionally trims the <br> tags and breaking and non breaking spaces as well
 *
 * @since 1.0.0
 * @access public
 * @param string $content
 * @return string
 */
function hybridextend_trim( $content ) {
	$content = trim( $content, " \t\n\r\0\x0B\xC2\xA0" ); // trim non breaking spaces as well
	$content = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $content);
	$content = preg_replace('/(?:<br\s*\/?>\s*)+$/', '', $content);
	$content = trim( $content, " \t\n\r\0\x0B\xC2\xA0" ); // trim non breaking spaces as well
	return $content;
}

/**
 * Trim a string to defined length
 *
 * @since 2.1.6
 * @access public
 * @param string $content
 * @param int $words
 * @return string
 */
function hybridextend_trim_content( $raw, $words ) {
	$text = $raw;
	$text = strip_shortcodes( $text );
	// $text = apply_filters( 'the_content', $text );
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = wp_trim_words( $text, $words, '' );
	return apply_filters( 'wp_trim_excerpt', $text, $raw );
}

/**
 * Custom Excerpt Length
 *
 * @since 2.1.11
 * @access public
 * @param int $words
 * @return string
 */
function hybridextend_get_excerpt( $words ) {
	if ( empty( $words ) ) {
		return apply_filters( 'the_excerpt', get_the_excerpt() );
	} else {
		global $hoot_theme;
		$hoot_theme->excerpt_customlength = $words;
		add_filter( 'excerpt_length', 'hybridextend_getexcerpt_customlength', 99999 );
		$return = apply_filters( 'the_excerpt', get_the_excerpt() );
		remove_filter( 'excerpt_length', 'hybridextend_getexcerpt_customlength', 99999 );
		unset( $hoot_theme->excerpt_customlength );
		return $return;
	}
}

/**
 * Custom Excerpt Length if set
 *
 * @since 2.1.11
 * @access public
 * @param int $length
 * @return int
 */
function hybridextend_getexcerpt_customlength( $length ){
	global $hoot_theme;
	if ( !empty( $hoot_theme->excerpt_customlength ) )
		return $hoot_theme->excerpt_customlength;
	else
		return $length;
}

/**
 * Check an array for empty values and return array
 *
 * @since 1.1.11
 * @access public
 * @param array $check
 * @param array $replace
 * @return array
 */
function hybridextend_array_empty( &$check, $replace = array() ){
	if ( is_array( $check ) && !empty( $check ) ) {
		if ( is_array( $replace ) && !empty( $replace ) )
			$check = $replace;
		else
			$check = array();
	} else $check = array();
	return $check;
}

/**
 * Insert into associative array at a specific location
 *
 * @since 1.1.1
 * @access public
 * @param array $insert
 * @param array $target
 * @param int|string $location 0 based position, or key in $target
 * @param string $order 'before' or 'after'
 * @return array
 */
function hybridextend_array_insert( $insert, $target, $location, $order = 'before' ) {

	if ( !is_array( $insert ) || !is_array( $target ) )
		return $target;

	if ( is_int( $location ) ) {

		if ( $order == 'after' )
			$location++;
		$target = array_slice( $target, 0, $location, true ) +
					$insert +
					array_slice( $target, $location, count( $target ) - 1, true );
		return $target;

	} elseif ( is_string( $location ) ) {

		$count = ( $order == 'after' ) ? 1 : 0;
		foreach ( $target as $key => $value ) {
			if ( $key === $location ) {
				$target = array_slice( $target, 0, $count, true ) +
							$insert +
							array_slice( $target, $count, count( $target ) - 1, true );
				return $target;
			}
			$count++;
		}
		// $location not found. So lets just return a simple array merge
		return array_merge( $target, $insert );

	}

	// Just for brevity
	return $target;
}

/**
 * Helper function for getting the minified script uri if available.
 *
 * @since 2.0.0
 * @access public
 * @param string $location
 * @param string $return uri or path
 * @return string
 */
function hybridextend_locate_script( $location, $return = 'uri' ) {
	$location = preg_replace( array(
		'/\.min\.css$/',
		'/\.css$/',
		), '', $location );
	return hybridextend_locate_uri( $location, 'js', $return );
}

/**
 * Helper function for getting the minified style uri if available.
 *
 * @since 2.0.0
 * @access public
 * @param string $location
 * @param string $return uri or path
 * @return string
 */
function hybridextend_locate_style( $location, $return = 'uri' ) {
	$location = preg_replace( array(
		'/\.min\.js$/',
		'/\.js$/',
		), '', $location );
	return hybridextend_locate_uri( $location, 'css', $return );
}

/**
 * Helper function for getting the minified script/style uri if available.
 *
 * @since 2.0.0
 * @access public
 * @param string $location absolute or relative path
 * @param string $type
 * @param string $return uri or path
 * @return string
 */
function hybridextend_locate_uri( $location, $type, $return = 'uri' ) {

	$location = str_replace( array(
		HYBRID_PARENT_URI . 'premium/',
		HYBRID_PARENT . 'premium/',
		HYBRID_PARENT_URI,
		HYBRID_PARENT,
		), '', $location );

	$pattern = apply_filters( 'hybridextend_locate_uri_extension_pattern', array( '/\.min\.' . $type . '$/', '/\.' . $type . '$/' ) );
	$location = preg_replace( $pattern, '', $location );

	if ( is_admin() )
		$loadminified = true;
	elseif ( defined( 'HYBRIDEXTEND_DEBUG' ) )
		$loadminified = ( HYBRIDEXTEND_DEBUG ) ? false : true;
	else
		$loadminified = hoot_get_mod( 'load_minified', 0 );

	/** Prepare Locations **/

	$locations = array();
	if ( is_child_theme() ) {

		if ( $loadminified )
			$locations['child-default-min'] = array(
				'path' => HYBRID_CHILD . $location . '.min.' . $type,
				'uri'  => HYBRID_CHILD_URI . $location . '.min.' . $type,
				);

		$locations['child-default'] = array(
			'path' => HYBRID_CHILD . $location . '.' . $type,
			'uri'  => HYBRID_CHILD_URI . $location . '.' . $type,
			);

	}

	if ( $loadminified )
		$locations['default-min'] = array(
			'path' => HYBRID_PARENT . $location . '.min.' . $type,
			'uri'  => HYBRID_PARENT_URI . $location . '.min.' . $type,
			);

	$locations['default'] = array(
		'path' => HYBRID_PARENT . $location . '.' . $type,
		'uri'  => HYBRID_PARENT_URI . $location . '.' . $type,
		);

	$locations = apply_filters( 'hybridextend_locate_uri', $locations, $location, $type, $loadminified );

	/** Locate the file **/

	$located = array( 'path' => '', 'uri' => '' );
	foreach ( $locations as $locate ) {
		if ( file_exists( $locate['path'] ) ) {
			$located = $locate;
			break;
		}
	}

	if ( $return == 'path' )
		return $located['path'];
	else
		return $located['uri'];

}

/**
 * A class of helper functions to cache and build options
 * 
 * @since 1.0.0
 */
class HybridExtend_Options_Helper {

	/**
	 * Utility functions for processing list count
	 *
	 * @since 2.0.0
	 * @return int
	 */
	static function countval( $number ){

		if ( $number===false)
			return HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT;

		$number = intval( $number );
		if ( empty( $number ) || $number < 0 )
			return 0;

		return $number;
	}

	/**
	 * Get pages array
	 *
	 * @since 1.0.0
	 * @param int $number
	 * @param string $post_type for custom post types
	 * @return array
	 */
	static function get_pages( $number, $post_type = 'page' ){
		$number = ( !$number ) ? -1 : $number;
		$pages = array();
		$the_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $number, 'orderby' => 'post_title', 'order' => 'ASC', 'post_status' => 'publish' ) );
		// Prietable plugin (wpalchemy) bug compatibility: We cannot run a custom loop (with
		// $the_query->the_post() ) since this will set global $post (initially empty before looping
		// through custom query). Even wp_reset_postdata() doesnt set global $post back to empty
		// wpalchemy uses global $post->ID, and hence gets the ID of last page instead of empty (at
		// a later hook, it would have got its easy table's post ID)
		// All this happens in Metabox.php file in easy-pricing-tables (hooked to 'admin_init' at 10)
		// if ( $the_query->have_posts() ) :
		// 	while ( $the_query->have_posts() ) : $the_query->the_post();
		// 		$pages[ get_the_ID() ] = get_the_title();
		// 	endwhile;
		// 	wp_reset_postdata();
		// endif;
		if ( !empty( $the_query->posts ) )
			foreach ( $the_query->posts as $post ) if( !empty( $post->ID ) )
				$pages[ $post->ID ] = ( empty( $post->post_title ) ) ? '' : apply_filters( 'the_title', $post->post_title, $post->ID );
		return $pages;
	}

	/**
	 * Get posts array
	 *
	 * @since 1.0.0
	 * @param int $number
	 * @return array
	 */
	static function get_posts( $number ){
		$number = ( absint( $number ) ) ? absint( $number ) : 0;
		$posts = array();
		$object = get_posts("numberposts=$number");
		foreach ( $object as $post ) {
			$posts[ $post->ID ] = $post->post_title;
		}
		return $posts;
	}

	/**
	 * Get terms array
	 *
	 * @since 2.0.0
	 * @param int $number
	 * @param string $taxonomy
	 * @return array
	 */
	static function get_terms( $number, $taxonomy = 'category' ){
		$number = ( absint( $number ) ) ? absint( $number ) : 0;
		$terms = array();
		$object = (array) get_terms( array( 'taxonomy' => $taxonomy, 'number' => $number ) );
		foreach ( $object as $term )
			$terms[$term->term_id] = $term->name;
		return $terms;
	}

	/**
	 * Pull all the categories into an array
	 *
	 * @since 1.0.0
	 * @param int $number false for default, empty or -1 for all
	 * @return array
	 */
	static function categories( $number = false ){
		$number = self::countval( $number );

		if ( $number == HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT ) {
			static $options_categories_default = array();
			if ( empty( $options_categories_default ) )
				$options_categories_default = self::get_terms( $number, 'category' );
			return $options_categories_default;
		}

		elseif ( empty( $number ) ) {
			static $options_categories = array();
			if ( empty( $options_categories ) )
				$options_categories = self::get_terms( $number, 'category' );
			return $options_categories;
		}

		else
			return self::get_terms( $number, 'category' );

	}

	/**
	 * Pull all the tags into an array
	 *
	 * @since 1.0.0
	 * @param int $number false for default, empty or -1 for all
	 * @return array
	 */
	static function tags( $number = false ){
		$number = self::countval( $number );

		if ( $number == HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT ) {
			static $options_tags_default = array();
			if ( empty( $options_tags_default ) )
				$options_tags_default = self::get_terms( $number, 'post_tag' );
			return $options_tags_default;
		}

		elseif ( empty( $number ) ) {
			static $options_tags = array();
			if ( empty( $options_tags ) )
				$options_tags = self::get_terms( $number, 'post_tag' );
			return $options_tags;
		}

		else
			return self::get_terms( $number, 'post_tag' );

	}

	/**
	 * Pull all the pages into an array
	 *
	 * @since 1.0.0
	 * @param int $number false for default, empty or -1 for all
	 * @return array
	 */
	static function pages( $number = false ){
		$number = self::countval( $number );

		if ( $number == HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT ) {
			static $options_pages_default = array();
			if ( empty( $options_pages_default ) )
				$options_pages_default = self::get_pages( $number, 'page' );
			return $options_pages_default;
		}

		elseif ( empty( $number ) ) {
			static $options_pages = array();
			if ( empty( $options_pages ) )
				$options_pages = self::get_pages( $number, 'page' );
			return $options_pages;
		}

		else
			return self::get_pages( $number, 'page' );

	}

	/**
	 * Pull all the posts into an array
	 *
	 * @since 1.0.0
	 * @param int $number false for default, empty or -1 for all
	 * @return array
	 */
	static function posts( $number = false ){
		$number = self::countval( $number );

		if ( $number == HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT ) {
			static $options_posts_default = array();
			if ( empty( $options_posts_default ) )
				$options_posts_default = self::get_posts( $number );
			return $options_posts_default;
		}

		elseif ( empty( $number ) ) {
			static $options_posts = array();
			if ( empty( $options_posts ) )
				$options_posts = self::get_posts( $number );
			return $options_posts;
		}

		else
			return self::get_posts( $number );

	}

	/**
	 * Pull all the cpt posts into an array
	 *
	 * @since 1.1.1
	 * @param string $post_type for custom post types
	 * @param int $number Set to -1 for all pages
	 * @param string $append Append a value
	 * @return array
	 */
	static function cpt( $post_type = 'page', $number = false, $append = false ){
		$number = self::countval( $number );

		if ( $number == HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT ) {
			static $cpt_default = array();
			if ( empty( $cpt_default[ $post_type ] ) )
				$cpt_default[ $post_type ] = self::get_pages( $number, $post_type );
			$return = $cpt_default[ $post_type ];
		}

		elseif ( empty( $number ) ) {
			static $cpt = array();
			if ( empty( $cpt[ $post_type ] ) )
				$cpt[ $post_type ] = self::get_pages( $number, $post_type );
			$return = $cpt[ $post_type ];
		}

		else
			$return = self::get_pages( $number, $post_type );

		$return = ( $append ) ? array( $append ) + $return : $return;
		return $return;

	}

}

/* == media == */

/**
 * Registers custom image sizes for the theme. 
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_register_image_sizes() {
	$sizes = array();
	$sizes = apply_filters( 'hybridextend_custom_image_sizes', $sizes );

	foreach ( $sizes as $name => $size ) :

		$default_size = array(
			'label'          => '',
			'width'          => 0,
			'height'         => 0,
			'crop'           => false,
			'show_in_editor' => false,
		);
		$size = wp_parse_args( $size, $default_size );

		/* Add image size if its not a Reserved Name */
		if ( $name != 'thumb' && $name != 'thumbnail' && $name != 'medium' && $name != 'large' && $name != 'post-thumbnail' ) {

			if ( intval( $size['width'] ) != 0 || intval( $size['height'] ) != 0 )
				add_image_size( $name, intval( $size['width'] ), intval( $size['height'] ), $size['crop'] );

		} elseif ( $name == 'post-thumbnail' ){

			/* Sets the 'post-thumbnail' size. */
			set_post_thumbnail_size( $size['width'], $size['height'], $size['crop'] );

		}

	endforeach;

}
add_action( 'init', 'hybridextend_register_image_sizes', 0 );

/**
 * Get the image size to use in a span/column of the CSS grid
 *        Case 1: $grid can be a container span, for when a spanN is in a grid which itself is a spanN/Column
 *        Case 2: Account for responsive spans i.e. set a minimum span size for smaller spans so that mobile viewports
 *                will show bigger width images for available screen space. Example: span1,2,3 will have image sizes
 *                corresponding to span4, so that in mobile view where all spans have 100% width, images are displayed
 *                more nicely!
 *        Case 3: Maybe find a robust (not hard coded) way to account for span padding as well (curently $swidth
 *                does this by using $gridadjust)
 *
 * @since 1.0.0
 * @access public
 * @param string $span span size or column size
 * @param NULL|bool $crop get only cropped if true, only noncropped if false, either for anything else.
 * @param int $gridadjust Grid's Width Adjustment for various paddings (possible value 80)
 * @return string
 */
function hybridextend_get_image_size_name( $span, $crop=NULL, $gridadjust=0 ) {

	/* Get the Grid (int)Width from Options else Default */
	$grid = 1260;
	if ( function_exists( 'hybridextend_customize_get_choices' ) ) {
		$grid_choices = hybridextend_customize_get_choices('site_width');
		if ( $grid_choices )
			$grid = max( array_map( 'absint', array_keys( $grid_choices ) ) );
	}
	$grid -= $gridadjust;

	/* Get the Span/Column factor */
	if ( strpos( $span, 'span-' ) !== false ) {
		$pieces = explode( "span-", $span );
		$factor = $pieces[1];
	} elseif ( strpos( $span, 'column-' ) !== false ) {
		$pieces = explode( "column-", $span );
		$factors = explode( "-", $pieces[1] );
		$factor = ( $factors[0] * 12 ) / $factors[1];
	} else {
		return false;
	}

	/* Responsive Grid: Any span below 3 gets an image size fit for atleast span3 to display nicely on smaller screens */
	$factorint = intval( $factor );
	$factor = ( empty( $factorint ) || round( $factor ) < 3 ) ? 3 : round( $factor );

	/* Get width array arranged in ascending order */
	if ( $crop === true )
		$iwidths = hybridextend_get_image_sizes( 'sort_by_width_crop' );
	elseif ( $crop === false )
		$iwidths = hybridextend_get_image_sizes( 'sort_by_width_nocrop' );
	else
		$iwidths = hybridextend_get_image_sizes( 'sort_by_width' );

	/* Get Image size corresponding to span width */
	$swidth = ( $factor / 12 ) * $grid;
	foreach ( $iwidths as $name => $iwidth ) {
		if ( (int)$swidth <= (int)$iwidth )
			return $name;
	}

	/* If this was a crop/no-crop request and we didn't find any image size, then search all available sizes. */
	if ( $crop === true || $crop === false ){
		$iwidths = hybridextend_get_image_sizes( 'sort_by_width' );
		foreach ( $iwidths as $name => $iwidth ) {
			if ( (int)$swidth <= (int)$iwidth )
				return $name;
		}
	}

	/* Full size image (largest width) */
	return 'full';

}

/**
 * Get all (or one) registered image sizes with width and height
 *
 * @since 1.0.0
 * @access public
 * @param string $return specific image size to return, or 'sort_by_width' to return array sorted by inc. widths,
 *                       or 'sort_by_width_crop' for sorted (by width) only cropped sizes, or 'sort_by_width_nocrop'
 *                       for sorted (by width) only noncropped sizes
 * @return array
 */
function hybridextend_get_image_sizes( $return = '' ) {
	static $sizes = array(); // cache
	static $sort_by_width = array();
	static $sort_by_width_crop = array();
	static $sort_by_width_nocrop = array();

	if ( empty( $sizes ) ) {
		global $_wp_additional_image_sizes;
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach( $get_intermediate_image_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ]['width'] = $_wp_additional_image_sizes[ $_size ]['width'];
				$sizes[ $_size ]['height'] = $_wp_additional_image_sizes[ $_size ]['height'];
				$sizes[ $_size ]['crop'] = $_wp_additional_image_sizes[ $_size ]['crop'];
			}
			// Create additional arrays
			if ( isset( $sizes[ $_size ]['width'] ) ){
				$sort_by_width[ $_size ] = $sizes[ $_size ]['width'];
				if ( $sizes[ $_size ]['crop'] )
					$sort_by_width_crop[ $_size ] = $sizes[ $_size ]['width'];
				else
					$sort_by_width_nocrop[ $_size ] = $sizes[ $_size ]['width'];
			}
		}

		// Note: With asort, if 2 values are equal, their order in resulting array is undefined. Instead we can use:
		// uksort($sort_by_width, function($x, $y) use ($sort_by_width) {
		// 	if($sort_by_width[$x]==$sort_by_width[$y])
		// 		return $x<$y?-1:$x!=$y;
		// 	return $sort_by_width[$x]-$sort_by_width[$y];
		// });
		asort( $sort_by_width, SORT_NUMERIC );
		asort( $sort_by_width_crop, SORT_NUMERIC );
		asort( $sort_by_width_nocrop, SORT_NUMERIC );
	}

	if ( $return ) {
		if ( 'sort_by_width' == $return ){
			return $sort_by_width;
		} elseif ( 'sort_by_width_crop' == $return ){
			return $sort_by_width_crop;
		} elseif ( 'sort_by_width_nocrop' == $return ){
			return $sort_by_width_nocrop;
		} elseif ( isset( $sizes[ $return ] ) ) {
			return $sizes[ $return ];
		} else {
			return false;
		}
	}
	return $sizes;
}

/**
 * Get an attachment ID given a URL.
 * @credit https://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
 *
 * @since 2.1.8
 * @access public
 * @param string $url
 * @return int Attachment ID on success, 0 on failure
 */
function hybridextend_get_attachid_url( $url ) {
	$url = esc_url( $url );
	if ( empty( $url ) )
		return 0;

	$attachment_id = 0;
	$dir = wp_upload_dir();
	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
		$file = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			)
		);
		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta = wp_get_attachment_metadata( $post_id );
				$original_file = basename( $meta['file'] );
				// $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				// if ( $original_file === $file || in_array( $file, $cropped_image_files ) )
				$uploads = wp_upload_dir();
				$relurl = str_replace( trailingslashit($uploads['baseurl']), '', $url ); // 2015/01/slide-01-150x150.jpg
				if ( $meta['file'] == $relurl ) :
					$attachment_id = $post_id;
					break;
				elseif( !empty( $meta['sizes'] ) ) :
					foreach ( $meta['sizes'] as $metasize ) :
						$relurl2 = str_replace( $metasize['file'], $original_file, $relurl );
						if ( $meta['file'] == $relurl2 ) {
							$attachment_id = $post_id;
							break;
						}
					endforeach;
				endif;
			}
		}
	}
	// echo "================ {$url} \n {$relurl} \n {$relurl2} \n ".$meta['file']." \n {$attachment_id}";
	return $attachment_id;
}

/* == head == */

/**
 * Adds the profile link to the header.
 * This will get removed in the future as it has become less and less relevant with HTML5
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_link_profile() {
	echo '<link rel="profile" href="http://gmpg.org/xfn/11" />' . "\n";
}
add_action( 'wp_head', 'hybridextend_link_profile', 3 );

/* == misc. (core) == */

/**
 * Add debug info if HYBRIDEXTEND_DEBUG is true
 *
 * @since 2.0.0
 * @access public
 */
function hybridextend_debug_info( $msg, $return = false ) {
	static $string = '';
	if ( !empty( $msg ) )
		$string = $string . $msg;
	if ( $return )
		return $string;
}

/**
 * Add debug info if HYBRIDEXTEND_DEBUG is true
 *
 * @since 2.0.0
 * @access public
 */
function hybridextend_add_debug_info() {
	if ( current_user_can('manage_options') ) {
		echo "\n<!-- HYBRIDEXTEND DEBUG INFO-->\n" ;
		if ( function_exists( 'hybridextend_developer_data' ) )
			echo "\n<!-- " . hybridextend_developer_data() . "-->\n" ;
		$info = ( defined( 'HYBRIDEXTEND_DEBUG' ) && true === HYBRIDEXTEND_DEBUG ) ? hybridextend_debug_info( '', true ) : '';
		if ( $info )
			echo "<!--\n" . $info . "\n-->" ;
	}
}
add_action( 'wp_footer', 'hybridextend_add_debug_info' );
add_action( 'admin_footer', 'hybridextend_add_debug_info' );

/**
 * Display Site Performance Data
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_developer_data( $commented = true ) {
	ob_start();
	echo get_num_queries() . ' ' . __( 'queries.', 'hybrid-core' ) . ' ';
	timer_stop(1);
	echo esc_html( __( ' seconds. ', 'hybrid-core' ) . ( memory_get_peak_usage(1) / 1024 ) / 1024 . 'MB' );
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
}

/**
 * Enhanced current_theme_supports to check for arguments
 *
 * @since 2.1.0
 * @access public
 * @param string $feature
 * @param string $arg
 */
function hybridextend_theme_supports( $feature, $arg = '' ) {
	if ( !empty( $arg ) ) {
		$support = get_theme_support( $feature );
		return ( isset( $support[0] ) && is_array( $support[0] ) && in_array( $arg, $support[0] ) );
	} else {
		return current_theme_supports( $feature );
	}
}

/* == attr == */

/**
 * Outputs an HTML element's attributes.
 *
 * @since 1.0.0
 * @access public
 * @param string  $slug     The slug/ID of the element (e.g., 'sidebar').
 * @param string $context  A specific context (e.g., 'primary').
 * @param string|array $attr  Addisitonal css classes to add / Array of attributes to pass in (overwrites filters).
 * @return void
 */
function hybridextend_attr( $slug, $context = '', $attr = '' ) {
	echo hybridextend_get_attr( $slug, $context, $attr );
}

/**
 * Gets an HTML element's attributes.  This function is actually meant to be filtered by theme authors, plugins, 
 * or advanced child theme users.  The purpose is to allow folks to modify, remove, or add any attributes they 
 * want without having to edit every template file in the theme.  So, one could support microformats instead 
 * of microdata, if desired.
 *
 * @since 1.0.0
 * @access public
 * @param string  $slug    The slug/ID of the element (e.g., 'sidebar').
 * @param string $context  A specific context (e.g., 'primary').
 * @param string|array $attr  Addisitonal css classes to add / Array of attributes to pass in (overwrites filters).
 * @return string
 */
function hybridextend_get_attr( $slug, $context = '', $attr = '' ) {

	/* Define variables */
	$out     = '';
	$classes = ( is_string( $attr ) ) ? $attr : '';
	$classes = ( is_array( $attr ) && !empty( $attr['classes'] ) && is_string( $attr['classes'] ) ) ? $classes . ' ' . $attr['classes'] : $classes;
	$attr    = ( is_array( $attr ) ) ? $attr : array();

	/* Prepare custom Classes if any */
	if ( !empty( $classes ) ) {
		$attr['classes'] = '';
		$classes = explode( " ", $classes );
		foreach ( $classes as $class )
			$attr['classes'] .= ' ' . sanitize_html_class( $class );
	}

	/* Build attrs */
	// $slugger = str_replace( "-", "_", $slug );
	$attr = apply_filters( "hybrid_attr_{$slug}", $attr, $context );
	if ( !isset( $attr['class'] ) )
		$attr['class'] = $slug;

	/* Add custom Classes if any */
	if ( isset( $attr['classes'] ) ) {
		$attr['class'] .= ' ' . $attr['classes'];
		unset( $attr['classes'] );
	}

	/* Create attributes */
	// Get ID and class first
	foreach ( array( 'id', 'class' ) as $key )
		if ( !empty( $attr[$key] ) ) {
			$out .= sprintf( ' %s="%s"', $key, esc_attr( $attr[$key] ) );
			unset( $attr[ $key ] );
		}
	// Remaining attributes
	foreach ( $attr as $name => $value )
		if ( $value !== false )
			$out .= !empty( $value ) ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );

	return trim( $out );
}