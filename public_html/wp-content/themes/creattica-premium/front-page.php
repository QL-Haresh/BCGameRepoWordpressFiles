<?php
// Let child theme modify template structure
do_action( 'hoot_template_frontpage' );
if ( apply_filters( 'hoot_disable_widgetized_frontpage', false ) )
	return;

// Loads the header.php template.
get_header();

// Template structure
$fpg_sidebar = apply_filters( 'hoot_frontpage_sidebar', 'none' );
$fpg_content_context = ( $fpg_sidebar == 'none' ) ? 'none' : '';
$fpg_content_grid = ( $fpg_sidebar == 'none' ) ? 'hgrid-stretch' : 'hgrid';
$fpg_content_grid .= ( is_home() ) ? '' : ' main-content-grid';

// Template modification Hook
do_action( 'hoot_template_before_content_grid', 'frontpage.php' );
?>

<div id="frontpage-wrap" class="<?php echo $fpg_content_grid; ?>">

	<?php
	// Template modification Hook
	do_action( 'hoot_template_before_main', 'frontpage.php' );
	?>

	<main <?php hybridextend_attr( 'frontpage-content', $fpg_content_context ); ?>>

		<?php
		// Template modification Hook
		do_action( 'hoot_template_main_start', 'frontpage.php' );

		// Get Sections List
		$sections = hybridextend_sortlist( hoot_get_mod( 'frontpage_sections' ) );

		// Display Each Section according to ther sort order.
		if ( is_array( $sections ) && !empty( $sections ) ) :
			foreach ( $sections as $key => $section ) :
				if ( empty( $section[ 'sortitem_hide' ] ) ):

					// Set section type / context
					$context = ( strpos( $key, 'area_' ) === 0 ) ? str_replace( 'area_', '', $key ) : '';
					if ( ! empty( $context ) )
						$type = 'widgetarea';
					elseif ( $key == 'content' )
						$type = ( is_home() ) ? 'content-blog' : 'content-page';
					else
						$type = $key;
					$type = apply_filters( 'hoot_frontpage_sections_switch', $type, $key, $sections );

					// Exit the loop except main content for a paged post list (blog)
					if ( $type != 'content-blog' && apply_filters( 'hoot_fpg_paged_hidemodules', is_paged() ) )
						continue;

					// Set section background
					$module_bg = hoot_get_mod( "frontpage_sectionbg_{$key}-type" );
					$module_bg = ( empty( $module_bg ) ) ? 'none' : $module_bg;
					$background_class = 'module-bg-' . $module_bg;
					$background_class .= ( $module_bg == 'highlight' ) ? ' area-highlight' : '';

					// Allow child themes to have templates
					$custom_template = hybridextend_get_template_part( 'front-page-' . $key, $context );
					if ( $custom_template ):
						include( $custom_template );
					else:

						switch( $type ):

							// Display Widget Areas
							case 'widgetarea':
								$areakey = 'area_' . $context;
								$section['columns'] = isset( $section['columns'] ) ? $section['columns'] : '100';
								$structure = hoot_col_width_to_span( $section['columns'] );
								$count = count( $structure );
								$displayarea = false;
								for ( $c = 1; $c <= $count ; $c++ ) {
									if ( is_active_sidebar( "hoot-frontpage-{$areakey}_{$c}" ) ) {
										$displayarea = true;
										break;
									}
								}
								if ( $displayarea ) : ?>
									<div id="frontpage-<?php echo sanitize_html_class( $areakey ); ?>" <?php hybridextend_attr( 'frontpage-area', $areakey, 'frontpage-area frontpage-widgetarea ' . esc_attr( $background_class ) ); ?>>
										<div class="hgrid">
											<?php
											for ( $c = 1; $c <= $count ; $c++ ) {
												$area_id = "frontpage-{$areakey}_{$c}";
												$structurekey = $c - 1;
												?>
												<div id="<?php echo sanitize_html_class( $area_id ); ?>" class="<?php echo $structure[$structurekey]; ?>">
													<?php
													if ( is_active_sidebar( 'hoot-' . $area_id ) )
														dynamic_sidebar( 'hoot-' . $area_id );
													?>
												</div>
												<?php
											}
											?>
										</div>
									</div>
								<?php endif;
								break;

							// Display Blog Content
							case 'content-blog':
								wp_reset_query();
								?>
								<div id="frontpage-page-content" <?php hybridextend_attr( 'frontpage-area', $key, 'frontpage-area frontpage-pagecontent frontpage-blog-content ' . esc_attr( $background_class ) ); ?>>
									<?php
									if ( !empty( $section['title'] ) )
										echo '<div class="hgrid frontpage-page-content-title"><div class="hgrid-span-12"><h3 class="hoot-blogposts-title">' . wp_kses_post( $section['title'] ) . '</h3></div></div>';
									?>

									<div class="hgrid hoot-blogposts main-content-grid">
										<div id="content-archive" class="content <?php echo hoot_main_layout_class( 'content' ); ?>">
											<div id="content-wrap">

												<?php
												if ( have_posts() ) :

													echo '<div id="archive-wrap" class="archive-wrap">';

													// Template modification Hook
													do_action( 'hoot_loop_start', 'frontpage.php' );

													while ( have_posts() ) : the_post();
														// Loads the template-parts/content-{$post_type}.php template.
														hybridextend_get_content_template();
													endwhile;

													// Template modification Hook
													do_action( 'hoot_loop_end', 'frontpage.php' );

													echo '</div>';

													// Loads the template-parts/loop-nav.php template.
													get_template_part( 'template-parts/loop-nav' );

												else :
													// Loads the template-parts/error.php template.
													get_template_part( 'template-parts/error' );
												endif;
												?>

											</div><!-- #content-wrap -->
										</div><!-- #content -->

										<?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

									</div><!-- .main-content-grid -->
								</div>

								<?php break;

							// Display Page Content
							case 'content-page':
								wp_reset_query();
								?>
								<div id="frontpage-page-content" <?php hybridextend_attr( 'frontpage-area', $key, 'frontpage-area frontpage-pagecontent frontpage-staticpage-content ' . esc_attr( $background_class ) ); ?>>
									<?php
									if ( !empty( $section['title'] ) )
										echo '<div class="hgrid frontpage-page-content-title"><div class="hgrid-span-12"><h3 class="hoot-blogposts-title">' . wp_kses_post( $section['title'] ) . '</h3></div></div>';
									?>

									<div class="hgrid main-content-grid">
										<div id="content-staticpage" class="content <?php echo hoot_main_layout_class( 'content' ); ?>">
											<div id="content-wrap">
												<?php
												// Load the static page content
												while ( have_posts() ) : the_post();
													hybridextend_get_content_template();
												endwhile;?>
											</div><!-- #content-wrap -->
										</div><!-- #content -->

									<?php hybridextend_get_sidebar(); // Loads the sidebar.php template. ?>

									</div><!-- .main-content-grid -->
								</div>

								<?php break;

							// Display HTML Slider
							case 'slider_html': 
								$slider_width = hoot_get_mod( 'wt_html_slider_width' );
								$slider_grid = ( 'stretch' == $slider_width ) ? 'hgrid-stretch' : 'hgrid';

								ob_start();
								$frontpage_slider = apply_filters( 'hoot_frontpage_slider' , '', 'wt_cpt_slider_a' );

								if ( !empty( $frontpage_slider ) ) {
									echo $frontpage_slider;
								} else {
									global $hoot_theme;
									$slides = hoot_get_lite_slider( 'html' );

									if ( is_array( $slides ) && !empty( $slides ) ):

										/* Reset any previous slider */
										$hoot_theme->slider = array();
										$hoot_theme->sliderSettings = array(
											'class' => 'fpg-slider',
											'min_height' => intval( hoot_get_mod( 'wt_html_slider_min_height' ) ),
											);

										/* Create slider object */
										foreach ( $slides as $slide ) {
											if ( !empty( $slide['image'] ) || !empty( $slide['content'] ) || !empty( $slide['url'] ) ) {
												$hoot_theme->slider[] = $slide;
											}
										}

										/* Display Slider Template */
										get_template_part( 'template-parts/slider-html' );

									endif;
								}

								$slider_html = ob_get_clean();
								if ( !empty( $slider_html ) ) : ?>
								<div id="frontpage-html-slider" <?php hybridextend_attr( 'frontpage-area', $key, 'frontpage-area ' . esc_attr( $background_class ) ); ?>>
									<div class="frontpage-slider <?php echo $slider_grid; ?>">
										<div class="hgrid-span-12"><?php echo $slider_html; ?></div>
									</div>
								</div>
								<?php
								endif;

								break;

							// Display Image Slider
							case 'slider_img': 
								$slider_width = hoot_get_mod( 'wt_img_slider_width' );
								$slider_grid = ( 'stretch' == $slider_width ) ? 'hgrid-stretch' : 'hgrid';

								ob_start();
								$frontpage_slider = apply_filters( 'hoot_frontpage_slider' , '', 'wt_cpt_slider_b' );

								if ( !empty( $frontpage_slider ) ) {
									echo $frontpage_slider;
								} else {
									global $hoot_theme;
									$slides = hoot_get_lite_slider( 'image' );

									if ( is_array( $slides ) && !empty( $slides ) ):

										/* Reset any previous slider */
										$hoot_theme->slider = array();
										$hoot_theme->sliderSettings = array( 'class' => 'fpg-slider' );

										/* Create slider object */
										foreach ( $slides as $slide ) {
											if ( !empty( $slide['image'] ) ) {
												$hoot_theme->slider[] = $slide;
											}
										}

										/* Display Slider Template */
										get_template_part( 'template-parts/slider-image' );

									endif;
								}

								$slider_img = ob_get_clean();
								if ( !empty( $slider_img ) ) : ?>
									<div id="frontpage-img-slider" <?php hybridextend_attr( 'frontpage-area', $key, 'frontpage-area ' . esc_attr( $background_class ) ); ?>>
										<div class="frontpage-slider <?php echo $slider_grid; ?>">
											<div class="hgrid-span-12"><?php echo $slider_img; ?></div>
										</div>
									</div>
								<?php
								endif;

								break;

							default:
								// Allow mods to display content
								do_action( 'hoot_frontpage_sections', $type, $sections, $background_class, $context );

						endswitch;

					endif;

				endif;
			endforeach;
		endif;

		// Template modification Hook
		do_action( 'hoot_template_main_end', 'frontpage.php' );
		?>

	</main><!-- #content -->

	<?php
	// Template modification Hook
	do_action( 'hoot_template_after_main', 'frontpage.php' );
	?>

	<?php
	if ( $fpg_sidebar !== 'none' ) {
		hybridextend_get_sidebar(); // Loads the sidebar.php template.
	}
	?>

</div><!-- .hgrid -->

<?php get_footer(); // Loads the footer.php template. ?>