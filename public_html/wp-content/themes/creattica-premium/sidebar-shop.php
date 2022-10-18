<?php

global $hoot_theme;

// Dispay Sidebar if not a one-column layout
$sidebar_size = hoot_main_layout( 'primary-sidebar' );
if ( !empty( $sidebar_size ) ) :
?>

	<aside <?php hybridextend_attr( 'sidebar', 'primary' ); ?>>

		<?php

		// Template modification Hook
		do_action( 'hoot_template_sidebar_start', 'shop' );

		if ( is_active_sidebar( 'hoot-woocommerce-sidebar' ) ) : // If the sidebar has widgets.

			dynamic_sidebar( 'hoot-woocommerce-sidebar' ); // Displays the woocommerce sidebar.

		elseif ( current_user_can( 'edit_theme_options' ) && hybrid_widget_exists( 'WP_Widget_Text' ) ): // plugins like AMP can replace Text widget in widget factory

			the_widget(
				'WP_Widget_Text',
				array(
					'title'  => __( 'Woocommerce Sidebar', 'creattica-premium' ),
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					'text'   => sprintf( __( 'Woocommerce pages have a separate sidebar than the rest of your site. You can add custom widgets from the %swidgets screen%s in wp-admin.', 'creattica-premium' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>' ),
					'filter' => true,
				),
				array(
					'before_widget' => '<section class="widget widget_text">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>'
				)
			);

		endif; // End widgets check.

		// Template modification Hook
		do_action( 'hoot_template_sidebar_end', 'shop' );

		?>

	</aside><!-- #sidebar-primary -->

<?php
endif; // End layout check.