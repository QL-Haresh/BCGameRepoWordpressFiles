<?php
// Return if no icons to show
if ( empty( $icons ) || !is_array( $icons ) )
	return;
?>

<div class="social-icons-widget <?php echo 'social-icons-' . esc_attr( $size ); ?>"><?php

	/* Display Title */
	if ( $title )
		echo wp_kses_post( apply_filters( 'hoot_widget_title', $before_title . $title . $after_title, 'social-icons', $title, $before_title, $after_title ) );

	foreach( $icons as $key => $icon ) :
		if ( !empty( $icon['url'] ) && !empty( $icon['icon'] ) ) :

			$icon_class = sanitize_html_class( $icon['icon'] ) . '-block';

			if ( $icon['icon'] == 'fa-skype' ) :
				echo '<div class="social-icons-icon fa-skype-block">'
					. '<i class="' . hybridextend_sanitize_fa( $icon['icon'] ) . '"></i>'
					. hoot_get_skype_button_code ( $icon['url'] )
					. '</div>';
			else :

				if ( $icon['icon'] == 'fa-envelope' ) {
					$url = str_replace( array( 'http://', 'https://'), '', esc_url( $icon['url'] ) );
					$url = 'mailto:' . $url;
				} else {
					$url = esc_url( $icon['url'] );
				}
				$context = $icon['icon'];

				?><a href="<?php echo $url; ?>" <?php hybridextend_attr( 'social-icons-icon', $context, $icon_class ); ?>>
					<i class="<?php echo hybridextend_sanitize_fa( $icon['icon'] ); ?>"></i>
				</a><?php

			endif;

		endif;
	endforeach; ?>
</div>