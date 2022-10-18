<div class="contact-info-widget">

	<?php
	/* Display Title */
	if ( $title )
		echo wp_kses_post( apply_filters( 'hoot_widget_title', $before_title . $title . $after_title, 'contact-info', $title, $before_title, $after_title ) );
	?>

	<?php if ( !empty( $address ) ) : ?>
		<div class="contact-info-block">
			<div class="contact-info-icon"><i class="fas fa-home"></i></div>
			<div class="contact-info-text"><?php echo nl2br( $address ); ?></div>
		</div>
	<?php endif; ?>

	<?php if ( !empty( $phone ) ) : ?>
		<div class="contact-info-block">
			<div class="contact-info-icon"><i class="fas fa-phone"></i></div>
			<div class="contact-info-text"><a href="tel:<?php echo esc_attr( $phone ); ?>" <?php hybridextend_attr( 'contact-info-link', 'phone' ); ?>><?php echo $phone; ?></a></div>
		</div>
	<?php endif; ?>

	<?php if ( !empty( $email ) ) :
		$email = sanitize_email( $email ); ?>
		<div class="contact-info-block">
			<div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
			<div class="contact-info-text"><a href="mailto:<?php echo antispambot( $email, 1 ); ?>" <?php hybridextend_attr( 'contact-info-link', 'email' ); ?>><?php echo $email; ?></a></div>
		</div>
	<?php endif; ?>

	<?php
	if ( !empty( $profiles ) ) :
		foreach( $profiles as $key => $profile ) : ?>
			<div class="contact-info-block">
				<?php if ( $profile['icon'] == 'fa-skype' ) :
					echo '<div class="contact-info-skype fa-skype-block">'
						. '<i class="fab fa-skype"></i>'
						. hoot_get_skype_button_code ( $profile['url'] )
						. '</div>';
				elseif ( $profile['icon'] == 'fa-envelope' ) :
					echo '<div class="contact-info-icon"><i class="fas fa-envelope"></i></div>';
					if ( !empty( $profile['url'] ) ) {
						$profile['text'] = ( !empty( $profile['text'] ) ) ? $profile['text'] : $profile['url'];
						echo '<div class="contact-info-text"><a href="mailto:' . antispambot( $profile['url'], 1 ) . '" ' . hybridextend_get_attr( 'contact-info-link', 'email' ) . '>' . esc_html( $profile['text'] ) . '</a></div>';
					};
				else: ?>
					<div class="contact-info-icon"><i class="<?php echo hybridextend_sanitize_fa( $profile['icon'] ); ?>"></i></div>
					<div class="contact-info-text"><?php
						if ( !empty( $profile['url'] ) )
							echo '<a href="' . esc_url( $profile['url'] ) . '" ' . hybridextend_get_attr( 'contact-info-link', 'profile' ) . '>';
						if ( !empty( $profile['text'] ) )
							echo $profile['text'];
						if ( !empty( $profile['url'] ) )
							echo '</a>';
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php
		endforeach;
	endif;
	?>

</div>