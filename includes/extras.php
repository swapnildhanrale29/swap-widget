<?php

/**
 *  Widget Field Generator
 */
if ( ! function_exists( 'ast_generate_widget_fields' ) ) {

	function ast_generate_widget_fields( $self, $fields ) {		
		if( !empty($fields)  && is_array($fields) ) {
			foreach ($fields as $key => $value) {

				
				$_field_type 		= isset( $value['type'] ) 	? esc_attr( $value['type'] ) 	: '';
				$_field_class 		= isset( $value['class'] ) 	? trim( esc_attr( $value['class'] ) )	: '';
				$_field_id 			= isset( $value['id'] ) 	? esc_attr( $value['id'] )		: '';
				$_field_icon 		= isset( $value['icon'] ) 	? '<i class="dashicons ' . $value['icon'] . '"></i>' : '';
				$_field_name 		= isset( $value['name'] ) 	? $value['name'] 				: '';
				$_field_desc 		= isset( $value['desc'] ) 	? $value['desc'] 				: '';
				$_field_min 		= isset( $value['min'] ) 	? $value['min'] 				: '';
				$_field_max 		= isset( $value['max'] ) 	? $value['max'] 				: '';
				$_field_image_width = isset( $value['width'] ) 	? $value['width'] 				: '';

				//	Render get_field_id & get_field_name
				$__get_field_id 	= esc_attr( $self->get_field_id( $_field_id ) );
				$__get_field_name 	= esc_attr( $self->get_field_name( $_field_id ) );

				switch ( $_field_type ) {
					case 'number':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-number">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label>
										<input class="widefat" type="number" min="<?php echo $_field_min; ?>" max="<?php echo $_field_max; ?>" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr($value['default']); ?>"/>
									</div>
								<?php
						break;
					case 'colorpicker':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-colorpicker">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label><br/>
										<span><input class="cs-wp-color-picker ast-widgets-field-colorpicker widefat" type="text" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr($value['default']); ?>" /></span>
										<span><?php echo $_field_desc; ?></span>
									</div>
								<?php
						break;
					case 'section':
								?>
						            <div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-section">
										<span><?php echo $_field_icon; ?></i><?php echo $_field_name; ?></span>
									</div>
								<?php
						break;
					case 'checkbox':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-checkbox">
										<input id="<?php echo $__get_field_id; ?>" value='1' name="<?php echo $__get_field_name; ?>" type="checkbox" value="<?php echo $value['default']; ?>" <?php checked( '1', $value['default'] ); ?> />
					                	<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label>
				                	</div>
			                	<?php
		                break;
					case 'repeater':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-repeater">
										<div class="repeaters">
											<?php if( is_array( $value['options'] ) &&( count($value['options']) > 0 )) {

												for ($i=0; $i < count( $value['options'][0]['default'] ); $i++) { ?>
													<div class="repeater">
														<div class="fields">
															<?php foreach ($value['options'] as $k => $v) {
																if( is_array($v['default']) && count($v['default']) > 0 ) { ?>
																	<?php for($j=0; $j < count($v['default']); $j++) {
																		$id = $__get_field_id;
																		$name = $__get_field_name;
																		$child_v = $v['default'][$i]; ?>
																		<label for="<?php echo $id; ?>"><?php echo $v['name']; ?></label>
																		<input class="widefat" type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>[<?php echo $v['id']; ?>][]" value="<?php echo $child_v; ?>" />
																	<?php break; } ?>
																<?php }
															} ?>
														</div>
														<div class="controls">
															<span class="add">Add</span>
															<span class="remove">Remove</span>
														</div>
													</div>
													<?php
												}
											} ?>
										</div>
									</div>
								<?php
						break;
					case 'image':
									$img_url = ast_get_widget_image_meta( $value['default'], 'url', 'full' );
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-image">
										<div class="ast-field-image">
											<div class="ast-field-image-preview">
												<?php if( !empty( $img_url ) ) { ?>
													<div class="ast-field-image-preview-inner">
														<span class="ast-remove-image button">X</span><img src="<?php echo $img_url; ?>" />
													</div>
												<?php } ?>
											</div>
											<div class="ast-select-image button"><?php _e('Select an Image', 'astra'); ?></div>
											<input class="ast-field-image-meta" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>" type="hidden" value='<?php echo $value['default']; ?>'>
										</div>
				                	</div>
			                	<?php
		                break;
		            case 'radio':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-radio">
					                	<label><?php echo $_field_name; ?></label><br/>
						                <?php foreach ($value['options'] as $key => $val) { ?>

						                	<?php
						                		$c = $selected = '';
							                	if( $key == $value['default'] ) {
								                    $c = ' checked="checked" ';
								                    $selected = 'selected';
								                }

								                //	Update name with radio key for unique buttons
								                //	Use same field for name & id
								                $__get_field_key = $self->get_field_name( $_field_id . '-' . $key );
								            ?>
							                <input class="widefat" type="radio" <?php echo $c; ?> id="<?php echo $__get_field_key; ?>" name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr( $key ); ?>" />
							                <label for="<?php echo $__get_field_key; ?>">
							                	<?php echo $val; ?>
							               	</label><br/>

						                <?php } ?>
				                	</div>
			                	<?php
		                break;
		            case 'radio-image':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-radio-image">
					                	<label><?php echo $_field_name; ?></label><br/>
						                <?php foreach ($value['options'] as $key => $val) { ?>

						                	<?php
						                		$c = $selected = '';
							                	if( $key == $value['default'] ) {
								                    $c = ' checked="checked" ';
								                    $selected = 'selected';
								                }

								                //	Update name with radio key for unique buttons
								                //	Use same field for name & id
								                $__get_field_key = $self->get_field_name( $_field_id . '-' . $key );
								            ?>
							                <input class="widefat" type="radio" <?php echo $c; ?> id="<?php echo $__get_field_key; ?>" name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr( $key ); ?>" />
							                <label for="<?php echo $__get_field_key; ?>">
							                	<img class="<?php echo $selected; ?>" style="width:<?php echo $_field_image_width; ?>;" src="<?php echo $val; ?>" >
							               	</label>

						                <?php } ?>
				                	</div>
			                	<?php
		                break;
		            case 'select':
		            			?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-select">
					                	<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label>
					                	<select class="widefat" type="text" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>">
							                <?php
							                	foreach ($value['options'] as $option => $val) {
								                	if( $option == $value['default'] ) { ?>
									                    <option selected="selected" value="<?php echo $option; ?>"><?php echo $val; ?></option>
								                    <?php } else { ?>
								                    	<option value="<?php echo $option; ?>"><?php echo $val; ?></option>
								                    <?php } ?>
							                <?php } ?>
						            	</select>
				                	</div>
			                	<?php
		                break;
		            case 'spacing':
		            			?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-spacing">
					                	<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label><br/>
					                	<?php foreach ($value['options'] as $key => $val) { 
				                	    		
				                	    		//	Update name with radio key for unique buttons
								                //	Use same field for name & id
								                $__get_field_key = $self->get_field_name( $_field_id . '-' . $key );
								            ?>
								            <div class="spacing-field">
								            	<span><?php echo ucfirst( $key ); ?></span><br/>
							                	<input class="widefat spacing-field-<?php echo $key; ?>" placeholder="<?php echo ucwords( $key ); ?>" type="number" id="<?php echo $__get_field_key; ?>" name="<?php echo $__get_field_key; ?>" value="<?php echo esc_attr( $val ); ?>" />
								            </div>
						                <?php } ?>
				                	</div>
			                	<?php
		                break;
					case 'hidden':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-hidden">
										<input type="hidden" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr($value['default']); ?>"/>
									</div>
								<?php
						break;
					case 'text':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-text">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label>
										<input min="<?php echo $_field_min; ?>" max="<?php echo $_field_max; ?>" class="widefat" type="text" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr($value['default']); ?>"/>
										<span><?php echo $_field_desc; ?></span>
									</div>
								<?php
						break;
					case 'email':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-email">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name; ?></label>
										<input class="widefat" type="email" id="<?php echo $__get_field_id; ?>"
										       name="<?php echo $__get_field_name; ?>" value="<?php echo esc_attr($value['default']); ?>"/>
									</div>
								<?php
						break;
					case 'textarea':
								?>
									<div class="ast-widget-field <?php echo $_field_class; ?> ast-widget-field-textarea">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name ?></label>
										<textarea class="widefat" id="<?php echo $__get_field_id; ?>"
										          name="<?php echo $__get_field_name; ?>"
										          rows="5"><?php echo esc_attr($value['default']); ?></textarea>
									</div>
								<?php
						break;

					//	WordPress Menu
					case 'wp_menu':
								?>
									<p class="<?php echo $_field_class; ?>">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name ?></label><br/>

										<?php
											
											$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
											if ( ! $menus ) { ?>

												<p> <?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_url( admin_url( 'nav-menus.php' ) ) ); ?> </p>
											
											<?php } else { ?>

												<select class="widefat" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>">
													<option value="0"><?php _e( '&mdash; Select &mdash;', 'astra' ) ?></option>
													<?php
													foreach ( $menus as $menu ) {
														echo '<option value="' . $menu->term_id . '"'
														     . selected( $value['default'], $menu->term_id, false )
														     . '>' . $menu->name. '</option>';
													} ?>
												</select>
												
											<?php } ?>
									</p>
								<?php
						break;

					//	WordPress Categories DropDown
					case 'wp_dropdown_categories':
								?>
									<p class="<?php echo $_field_class; ?>">
										<label for="<?php echo $__get_field_id; ?>"><?php echo $_field_name ?></label><br/>

										<?php

											$categories = get_categories();

											if ( ! $categories ) { ?>

												<p> <?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_url( admin_url( 'nav-menus.php' ) ) ); ?> </p>
											
											<?php } else { ?>

												<select class="widefat" id="<?php echo $__get_field_id; ?>" name="<?php echo $__get_field_name; ?>">
													<option value="0"><?php _e( '&mdash; Select &mdash;', 'astra' ) ?></option>
													<?php
													foreach ( $categories as $cat ) {
														echo '<option value="' . $cat->term_id . '"'
														     . selected( $value['default'], $cat->term_id, false )
														     . '>' . $cat->name. '</option>';
													} ?>
												</select>
												
											<?php } ?>
									</p>
								<?php
						break;

			            
				}
			}
		}
	}

}

/**
 *
 */
if ( ! function_exists( 'str_replace_nth' ) ) {

	function str_replace_nth( $search, $replace, $subject, $nth ) {
		$found = preg_match_all( '/' . preg_quote( $search ) . '/', $subject, $matches, PREG_OFFSET_CAPTURE );
		if ( false !== $found && $found > $nth ) {
			return substr_replace( $subject, $replace, $matches[0][ $nth ][1], strlen( $search ) );
		}

		return $subject;
	}

}


/**
 * Register widgets depending on theme support
 */
if ( ! function_exists( 'register_astra_widget' ) ) {

	function register_astra_widget( $widgetslug ) {

		if ( current_theme_supports( $widgetslug ) && file_exists( SWAP_WIDGETS_DIR . 'classes/widgets/' . $widgetslug . '.php' ) ) {

			// include the widget file
			include SWAP_WIDGETS_DIR . 'classes/widgets/' . $widgetslug . '.php';

		}

	}
	
}

/**
 * Fallback check for ast_get_option
 */
if ( ! function_exists( 'ast_get_option_widgets' ) ) {

	function ast_get_option_widgets( $option, $subkeys = '', $default = '' ) {

		if ( function_exists( 'ast_get_option' ) ) {
			return ast_get_option( $option, $subkeys = '', $default = '' );
		} else {
			return apply_filters( "astra_option_{$option}", $default );
		}

	}

}

/**
 * Fallback check for ast_get_option
 */
if ( ! function_exists( 'ast_get_widget_image_meta' ) ) {

	function ast_get_widget_image_meta( $image_meta = '', $meta = 'url', $image_size = 'thumbnail', $return_meta = '' ) {

		if( !empty( $image_meta ) ) {
			//	Check default URL NOT CONTAIN character ^ and CONTAIN http
			if( !strstr($image_meta, '^') && strstr($image_meta, 'http') ) {
				$return_meta = $image_meta;
			} else {
				$image_data = explode('^', $image_meta );

				switch ($meta) {
					case 'id':		
									$return_meta = explode(':', $image_data[0] );
									$return_meta = $return_meta[1];
						break;
					case 'alt':		
									$return_meta = explode(':', $image_data[2] );
									$return_meta = $return_meta[1];
						break;
					case 'title':	
									$return_meta = explode(':', $image_data[3] );
									$return_meta = $return_meta[1];
						break;
					case 'url':
					default:
									$image_id = explode(':', $image_data[0] );
									$return_meta = wp_get_attachment_image_src( $image_id[1], $image_size );
									$return_meta = $return_meta[0];
						break;
				}
			}
		}

		return $return_meta;
	}

}

/**
 * Get widget instance
 *
 *	$instance 		array 		Widget instance
 *	$option 		string 		Widget instance key
 *	$default 		string 		Widget instance default value if not found any other
 *	$instance 		string 		Filter the widget value as per needed. Used string [filter-val] to check the filter val
 *
 *	@return string
 */
if ( ! function_exists( 'ast_get_widget_instance' ) ) {

	function ast_get_widget_instance( $instance = array(), $option = '', $default = '', $filter_val = '', $return_format = '' ) {

		if( count( $instance ) <= 0 ) {
			return '';
		}

		//	Set default / instance stored value
		if( array_key_exists($option, $instance) ) {
			$return_val = $instance[$option];
		} else {
			$return_val = $default;
		}

		/**
		*	Update default / instance stored value
		*	As per return format
		*/

		/**
		*	Image Field
		*
		*	Return formats are:
		*	- image_id
		*	- image_url|{image_size}
		*	- image_title
		*	- image_alt
		*/
		//	Return image URL with provided size
		if( strpos($return_format, 'image_url') !== false ) {
			$image_size = explode( '|', $return_format );
			if( array_key_exists(1, $image_size) ) {
				$image_size = $image_size[1];
			} else {
				$image_size = 'full';
			}
			$return_val = ast_get_widget_image_meta( $return_val, 'url', $image_size );
		}

		//	Return image ID, Title, Alt
		switch ( $return_format ) {
			case 'image_id':
								$return_val = ast_get_widget_image_meta( $return_val, 'id' );
				break;
			case 'image_title':
								$return_val = ast_get_widget_image_meta( $return_val, 'title' );
				break;
			case 'image_alt':
								$return_val = ast_get_widget_image_meta( $return_val, 'alt' );
				break;
		}


		//	Finally filter the output as per needed
		if( strpos($filter_val, '[filter-val]') !== false ) {
			$return_val = str_replace('[filter-val]', $return_val, $filter_val);
		}

		return $return_val;
	}

}

/**
 * Minify Dynamic CSS
 *
 *	@return string
 */
if ( ! function_exists( 'ast_minify_widget_css' ) ) {

	function ast_minify_widget_css( $css = '' ) {
		if( !empty($css) ) {
			$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
			$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);			
		}
		return $css;
	}

}
