<?php
// Return if no vcards to show
if ( empty( $vcards ) || !is_array( $vcards ) )
	return;

// Get border classes
$top_class = hoot_widget_border_class( $border, 0, 'topborder-');
$bottom_class = hoot_widget_border_class( $border, 1, 'bottomborder-');

// Get custom style attribute
$mt = ( !isset( $customcss['mt'] ) ) ? '' : $customcss['mt'];
$mb = ( !isset( $customcss['mb'] ) ) ? '' : $customcss['mb'];
$custommargin = hoot_widget_margin_style( $mt, $mb );

// Get total columns and set column counter
$columns = ( intval( $columns ) >= 1 && intval( $columns ) <= 5 ) ? intval( $columns ) : 3;
$column = $count = 1;

// Set clearfix to avoid error if there are no boxes
$clearfix = 1;
?>

<div class="vcards-widget-wrap <?php echo sanitize_html_class( $top_class ); ?>">
	<div class="vcards-widget-box <?php echo sanitize_html_class( $bottom_class ); ?>">
		<div class="vcards-widget" <?php echo $custommargin; ?>>

			<?php
			/* Display Title */
			if ( $title )
				echo wp_kses_post( apply_filters( 'hoot_widget_title', $before_title . $title . $after_title, 'vcards', $title, $before_title, $after_title ) );
			?>

			<div class="flush-columns">
				<?php foreach ( $vcards as $key => $vcard ) : ?>
					<?php if ( $column == 1 ) echo '<div class="vcard-row">'; ?>
					<div class="vcard-column <?php echo "hcolumn-1-{$columns} vcard-{$count}"; $count++; ?>">
						<div class="vcard highlight-typo">

							<?php if ( !empty( $vcard['image'] ) ) :
								$size = hoot_thumbnail_size( 'column-1-' . $columns );
								$size = apply_filters( 'vcard_img', $size );
								$src = wp_get_attachment_image_src( $vcard['image'], $size );
								if( empty( $src ) ) $src = wp_get_attachment_image_src( $vcard['image'], $size );
								if ( !empty( $src[0] ) ) : ?>
									<div class="vcard-visual vcard-image">
										<img src="<?php echo esc_url( $src[0] ) ?>" <?php hybridextend_attr( 'vcard-img' ); ?>>
									</div>
								<?php endif;
							endif; ?>

							<?php if ( !empty( $vcard['content'] ) ): ?>
								<div class="vcard-content <?php
									if ( !empty( $vcard['image'] ) ) echo 'vcard-content-hasimage';
									else echo 'no-visual';
									?>">
									<?php echo wp_kses_post( wpautop( $vcard['content'] ) ); ?>
								</div>
							<?php endif; ?>

							<?php
							// Get Links
							$has_links = false;
							for ( $i=1; $i <= 5 ; $i++ ) { 
								if ( !empty( $vcard["url{$i}"] ) ) {
									$has_links = true;
									break;
								}
							}
							?>
							<?php if ( $has_links ) : ?>
								<div class="vcard-links social-icons-widget social-icons-small">
									<?php
									for ( $i=1; $i <= 5 ; $i++ ) :
										if ( !empty( $vcard["url{$i}"] ) && !empty( $vcard["icon{$i}"] ) ) :

											if ( $vcard["icon{$i}"] != 'fa-skype' ) :
												$icon_class = sanitize_html_class( $vcard["icon{$i}"] ) . '-block';
												if ( $vcard["icon{$i}"] == 'fa-envelope' ) {
													$url = str_replace( array( 'http://', 'https://'), '', esc_url( $vcard["url{$i}"] ) );
													$url = 'mailto:' . $url;
												} else {
													$url = esc_url( $vcard["url{$i}"] );
												}
												$context = $vcard["icon{$i}"];
												?><div class="vcard-link">
													<a href="<?php echo $url ?>" <?php hybridextend_attr( 'vcard-link-inner', $context, $icon_class ); ?>>
														<i class="<?php echo hybridextend_sanitize_fa( $vcard["icon{$i}"] ); ?>"></i>
													</a>
												</div><?php
											else:
												?><div class="vcard-link">
													<div class="vcard-link-skype fa-skype-block">
														<i class="fab fa-skype"></i>
														<?php echo hoot_get_skype_button_code ( $vcard["url{$i}"] ); ?>
													</div>
												</div><?php
											endif;

										endif;
									endfor;
									?>
								</div>
							<?php endif; ?>

						</div>
					</div>

					<?php
					if ( $column == $columns ) {
						echo '</div>';
						$column = $clearfix = 1;
					} else {
						$clearfix = false;
						$column++;
					}
					?>
				<?php endforeach; ?>
				<?php if ( !$clearfix ) echo '</div>'; ?>
			</div>

		</div>
	</div>
</div>