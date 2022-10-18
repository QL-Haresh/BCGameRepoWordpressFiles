<?php
foreach ( $hybridextend_megamenu_options as $key => $option ) {

	$id = sanitize_html_class( $key );
	$toponly = ( isset( $option['top_level'] ) && true === $option['top_level'] ) ? true : false;
	$style = ( $toponly && 0 !== $depth ) ? ' style="display:none;" ' : '';
	$class = ( $toponly ) ? ' hybridextend_top_level_only ' : '';

	switch ( $option['type'] ) {

		case 'text':
			$class .= ( isset( $option['class'] ) && 'mini' == $option['class'] ) ? 'description-thin' : 'description-wide';
			?>
			<p class="field-<?php echo $id; ?> description <?php echo $class ?>" <?php echo $style; ?>>
				<label for="edit-menu-item-<?php echo $id . '-' . $item_id; ?>">
					<?php echo $option['name']; ?><br />
					<input type="text" id="edit-menu-item-<?php echo $id . '-' . $item_id; ?>" class="widefat code edit-menu-item-<?php echo $id; ?>" name="menu-item-<?php echo $id . '[' . $item_id . ']'; ?>" value="<?php echo esc_attr( $item->hybridextend_megamenu[ $key ] ); ?>" />
				</label>
			</p>
		<?php break;

		case 'checkbox': ?>
			<p class="field-<?php echo $id; ?> description description-wide <?php echo $class ?>" <?php echo $style; ?>>
				<label for="edit-menu-item-<?php echo $id . '-' . $item_id; ?>">
					<input type="checkbox" id="edit-menu-item-<?php echo $id . '-' . $item_id; ?>" value="1" name="menu-item-<?php echo $id . '[' . $item_id . ']'; ?>" <?php checked( $item->hybridextend_megamenu[ $key ], '1' ); ?>>
					<?php echo $option['name']; ?>
				</label>
			</p>
		<?php break;

		case 'textarea': ?>
			<p class="field-<?php echo $id; ?> description description-wide <?php echo $class ?>" <?php echo $style; ?>>
				<label for="edit-menu-item-<?php echo $id . '-' . $item_id; ?>">
					<?php echo $option['name']; ?><br />
					<textarea id="edit-menu-item-<?php echo $id . '-' . $item_id; ?>" class="widefat edit-menu-item-<?php echo $id; ?>" rows="3" cols="20" name="menu-item-<?php echo $id . '[' . $item_id . ']'; ?>"><?php echo esc_html( $item->hybridextend_megamenu[ $key ] ); // textarea_escaped ?></textarea>
					<?php if ( isset( $option['desc'] ) ) : ?>
						<span class="description"><?php echo $option['desc'] ?></span>
					<?php endif; ?>
				</label>
			</p>
		<?php break;

		case 'select': ?>
			<p class="field-<?php echo $id; ?> description description-wide <?php echo $class ?>" <?php echo $style; ?>>
				<label for="edit-menu-item-<?php echo $id . '-' . $item_id; ?>">
					<?php echo $option['name']; ?><br />
					<select id="edit-menu-item-<?php echo $id . '-' . $item_id; ?>" class="widefat edit-menu-item-<?php echo $id; ?>" name="menu-item-<?php echo $id . '[' . $item_id . ']'; ?>">
						<?php foreach ( $option['options'] as $opvalue => $opname ) { ?>
							<option value="<?php echo esc_attr( $opvalue ); ?>" <?php selected( $item->hybridextend_megamenu[ $key ], $opvalue ); ?>><?php echo esc_html( $opname ); ?></option>
						<?php } ?>
					</select>
				</label>
			</p>
		<?php break;

		case 'icon': ?>
			<p class="field-<?php echo $id; ?> description description-wide <?php echo $class ?>" <?php echo $style; ?>>
				<label for="edit-menu-item-<?php echo $id . '-' . $item_id; ?>">
					<span class="hybridext-icon-label"><?php echo $option['name']; ?></span>
					<?php $iconvalue = hybridextend_sanitize_fa( $item->hybridextend_megamenu[ $key ] ); ?>
					<input type="hidden" id="edit-menu-item-<?php echo $id . '-' . $item_id; ?>" class="widefat edit-menu-item-<?php echo $id; ?> hybridext-icon" name="menu-item-<?php echo $id . '[' . $item_id . ']'; ?>" value="<?php echo esc_attr( $iconvalue ) ?>" />
					<span id="<?php echo $id . '-' . $item_id . '-icon-picked' ?>" class="hybridext-icon-picked"><i class="<?php echo esc_attr( $iconvalue ) ?>"></i><span><?php _e( 'Select Icon', 'hybrid-core' ) ?></span></span>
					<span class="clear"></span>
					<span id="<?php echo $id . '-' . $item_id . '-icon-picker-box' ?>" class="hybridext-icon-picker-box">
						<span class="hybridext-icon-picker-list"><i class="fas fa-ban hybridext-icon-none" data-value="0" data-category=""><span><?php _e( 'Remove Icon', 'hybrid-core' ) ?></span></i></span>
						<?php
						$section_icons = hybridextend_enum_icons('icons');
						foreach ( hybridextend_enum_icons('sections') as $s_key => $s_title ) { ?>
							<span class="hybridext-iconsection-title"><?php echo $s_title ?></span>
							<span class="hybridext-icon-picker-list"><?php
							foreach ( $section_icons[$s_key] as $i_key => $i_class ) {
								$selected = ( $iconvalue == $i_class ) ? ' selected' : '';
								?><i class='<?php echo $i_class . $selected; ?>' data-value='<?php echo $i_class; ?>' data-category='<?php echo $s_key ?>'></i><?php
							} ?>
							</span><?php
						}
						?>
					</span>
				</label>
			</p>
		<?php break;

	}

}
?>