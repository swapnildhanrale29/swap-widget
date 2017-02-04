<?php

add_action( 'widgets_init', 'ast_widget_address' );
function ast_widget_address() {
	register_widget( 'Ast_Widget_Address' );
}
class Ast_Widget_Address extends WP_Widget {
	
	function __construct() {

		$widget_ops  = array(
			'classname' 	=> 'ast-widget-address',
			'description' 	=> __( 'Display address.', 'astra' )
		);
		
		$control_ops = array(
			'id_base' => 'ast-widget-address'
		);
		
		parent::__construct(
			'ast-widget-address',
			__( 'Astra: Address', 'astra' ),
			$widget_ops,
			$control_ops
		);

		//	Dynamically Generate CSS
		add_action('wp_enqueue_scripts', array( $this, 'css') );
	}
	
	/**
	 * Enqueue CSS on the frontend
	 */
	function css() {
	
		//	Get Instances
		if( count( $this->get_settings() ) <= 0 ) {
			return '';
		}
	
		$instance   = $this->get_settings()[$this->number];
		$text_align = ast_get_widget_instance( $instance, 'text_align', '' );
		
		//	Selector
		$selector = '#' . $this->id;
	
		ob_start();

		$css = ob_get_clean();

		$css = apply_filters( $this->id_base . '-css', $css );

		//	Minify CSS		
		$css = ast_minify_widget_css($css);

		//	Enqueue CSS
		wp_add_inline_style( 'ast-widgets-frontend-css', $css );	
	}

	function get_google_maps_locale() {

		$locale = get_locale();

		switch ( $locale ) {

			case 'en_AU' :
			case 'en_GB' :
			case 'pt_BR' :
			case 'pt_PT' :
			case 'zh_TW' :

				$locale = str_replace( '_', '-', $locale );

				break;

			case 'zh_CH' :

				$locale = 'zh-CN';

				break;

			default :

				$locale = substr( $locale, 0, 2 );

		}

		return $locale;

	}

	/**
	 * Defer Google Maps iframes with JavaScript.
	 *
	 * @action wp_footer
	 */
	public function map_defere_iframes_script() {

		?>
		<script type="text/javascript">
			window.onload = ( function() {
				var maps = document.getElementsByClassName( 'astra-address-widget-map' );
				for ( var i = 0; i < maps.length; i++ ) {
					var src = maps[i].getAttribute( 'data-src' );
					if ( src ) {
						maps[i].setAttribute( 'src', src );
						maps[i].removeAttribute( 'data-src' );
					}
				}
			} );
		</script>
		<?php

	}

	function widget( $args, $instance ) {
		extract( $args );

		$title		= apply_filters( 'widget_title', ast_get_widget_instance( $instance, 'title', '' ) );
		$address	= ast_get_widget_instance( $instance, 'address', '' );
		$phone		= ast_get_widget_instance( $instance, 'phone', '' );
		$mobile		= ast_get_widget_instance( $instance, 'mobile', '' );
		$fax		= ast_get_widget_instance( $instance, 'fax', '' );
		$email		= ast_get_widget_instance( $instance, 'email', '' );

		$icons		= ast_get_widget_instance( $instance, 'icons', '' );
		$label		= ast_get_widget_instance( $instance, 'label', '' );
		$map		= ast_get_widget_instance( $instance, 'map', '' );
		$map_pos	= ast_get_widget_instance( $instance, 'map_pos', '' );
		$text_align	= ast_get_widget_instance( $instance, 'text_align', '' );

		$defer_map_iframes = is_customize_preview() ? false : true;

		if ( $defer_map_iframes && ! has_action( 'wp_footer', [ $this, 'map_defere_iframes_script' ] ) ) {
			add_action( 'wp_footer', [ $this, 'map_defere_iframes_script' ] );
		}

		//	Before Widget
		echo $before_widget;

		?>

		<div class="ast-address-widget-wrapper widget-address-align-<?php echo $text_align; ?>">
		    <?php
		    if ( $title ) {
				echo $before_title . $title . $after_title ;
			} ?>

			<div class="address clearfix">
				<address class="widget-address widget-address-stack">
					
					<?php if( $map == 1 && $map_pos == 'top' && ! empty( $address ) ) { ?>
						<div class="widget-address-field">
						<?php printf(
							'<div class="has-map"><iframe %s="https://www.google.com/maps?q=%s&output=embed&hl=%s&z=%d" frameborder="0" class="astra-address-widget-map"></iframe></div>',
							( $defer_map_iframes ) ? 'src="" data-src' : 'src',
							urlencode( trim( strip_tags( $address ) ) ),
							urlencode( $this->get_google_maps_locale() ),
							14
						); ?>
						</div>
					<?php } ?>

					<?php if ( ! empty( $address ) ) { ?>
						<div class="widget-address-field">
							<span class="address-meta">
								<?php if( $label == 1 ) { ?>
									<span class="address-widget-label">
										<?php if( $icons == 1 ) { ?>
											<span class="icon-wrap">
												<i class="astra-icon-globe"></i>
											</span>
										<?php } ?>
										<label><?php _e( 'Address', 'astra' ); ?></label>
									</span>
								<?php } elseif( $icons == 1 ) { ?>
									<span class="icon-wrap">
										<i class="astra-icon-globe"></i>
									</span>
								<?php } ?>
								<div class="address-wrap">
									<?php echo nl2br( $address ); ?>
								</div>
							</span>
						</div>
					<?php } ?>
					
					<?php if ( ! empty( $phone ) ) { ?>
						<div class="widget-address-field">
							<span class="address-meta">
								<?php if( $label == 1 ) { ?>
									<span class="address-widget-label">
										<?php if( $icons == 1 ) { ?>
											<span class="icon-wrap">
												<i class="astra-icon-old-phone"></i>
											</span>
										<?php } ?>
										<label><?php _e( 'Phone', 'astra' ); ?></label>
									</span>
								<?php } elseif( $icons == 1 ) { ?>
									<span class="icon-wrap">
										<i class="astra-icon-old-phone"></i>
									</span>
								<?php } ?>
								<a href="tel:+<?php echo preg_replace( '/\D/', '', esc_attr($phone) ); ?>" ><?php echo esc_attr($phone); ?></a>
							</span>
						</div>
					<?php } ?>

					<?php if ( ! empty( $mobile ) ) { ?>
						<div class="widget-address-field">
							<span class="address-meta">
								<?php if( $label == 1 ) { ?>
									<span class="address-widget-label">
										<?php if( $icons == 1 ) { ?>
											<span class="icon-wrap">
												<i class="astra-icon-old-mobile"></i>
											</span>
										<?php } ?>
										<label><?php _e( 'Mobile', 'astra' ); ?></label>
									</span>
								<?php } elseif( $icons == 1 ) { ?>
									<span class="icon-wrap">
										<i class="astra-icon-old-mobile"></i>
									</span>
								<?php } ?>
								<a href="tel:+<?php echo preg_replace( '/\D/', '', esc_attr($mobile) ); ?>" ><?php echo esc_attr($mobile); ?></a>
							</span>
						</div>
					<?php } ?>
					
					<?php if ( ! empty( $fax ) ) { ?>
						<div class="widget-address-field">
							<span class="address-meta">
								<?php if( $label == 1 ) { ?>
									<span class="address-widget-label">
										<?php if( $icons == 1 ) { ?>
											<span class="icon-wrap">
												<i class="astra-icon-print"></i>
											</span>
										<?php } ?>
										<label><?php _e( 'Fax', 'astra' ); ?></label>
									</span>
								<?php } elseif( $icons == 1 ) { ?>
									<span class="icon-wrap">
										<i class="astra-icon-print"></i>
									</span>
								<?php } ?>
								<?php echo esc_attr($fax); ?>
							</span>
						</div>
					<?php } ?>
					
					<?php if ( ! empty( $email ) ) { $email = sanitize_email( $email ); ?>
						<div class="widget-address-field">
							<span class="address-meta">
								<?php if( $label == 1 ) { ?>
									<span class="address-widget-label">
										<?php if( $icons == 1 ) { ?>
											<span class="icon-wrap">
												<i class="astra-icon-mail"></i>
											</span>
										<?php } ?>
										<label><?php _e( 'Email', 'astra' ); ?></label>
									</span>
								<?php } elseif( $icons == 1 ) { ?>
									<span class="icon-wrap">
										<i class="astra-icon-mail"></i>
									</span>
								<?php } ?>
								<a href="mailto:<?php echo antispambot( $email ); ?>" ><?php echo antispambot( $email ); ?></a>
							</span>
						</div>
					<?php } ?>

					<?php if( $map == 1 && $map_pos == 'bottom' && ! empty( $address ) ) { ?>
						<div class="widget-address-field">
						<?php printf(
							'<div class="has-map"><iframe %s="https://www.google.com/maps?q=%s&output=embed&hl=%s&z=%d" frameborder="0" class="astra-address-widget-map"></iframe></div>',
							( $defer_map_iframes ) ? 'src="" data-src' : 'src',
							urlencode( trim( strip_tags( $address ) ) ),
							urlencode( $this->get_google_maps_locale() ),
							14
						); ?>
						</div>
					<?php } ?>

				</address>
			</div>
		</div>

		<?php

		//	After Widget
		echo $after_widget; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = wp_parse_args( $old_instance, $new_instance);
		return $new_instance;
	}
	
	function form( $instance ) {
		?>

			<div class="ast-widget">
				
				<?php

					$fields = array(
						array(
							'type' 	=> 'text',
							'id'	=> 'title',
							'name' => __( 'Title:', 'astra' ),
							'default' => ( isset( $instance['title'] ) && !empty( $instance['title'] ) ) ? $instance['title'] : '',
						),
						array(
							'type' 	=> 'textarea',
							'id'	=> 'address',
							'name' => __( 'Address:', 'astra' ),
							'default' => ( isset( $instance['address'] ) && !empty( $instance['address'] ) ) ? $instance['address'] : '',
						),
						array(
							'type' 	=> 'text',
							'id'	=> 'phone',
							'name' => __( 'Phone:', 'astra' ),
							'default' => ( isset( $instance['phone'] ) && !empty( $instance['phone'] ) ) ? $instance['phone'] : '',
						),
						array(
							'type' 	=> 'text',
							'id'	=> 'mobile',
							'name' => __( 'Mobile:', 'astra' ),
							'default' => ( isset( $instance['mobile'] ) && !empty( $instance['mobile'] ) ) ? $instance['mobile'] : '',
						),
						array(
							'type' 	=> 'text',
							'id'	=> 'fax',
							'name' => __( 'FAX:', 'astra' ),
							'default' => ( isset( $instance['fax'] ) && !empty( $instance['fax'] ) ) ? $instance['fax'] : '',
						),
						array(
							'type' 	=> 'text',
							'id'	=> 'email',
							'name' => __( 'Email:', 'astra' ),
							'default' => ( isset( $instance['email'] ) && !empty( $instance['email'] ) ) ? $instance['email'] : '',
						),
						array(
							'type' => 'section',
							'icon' => 'dashicons-admin-generic',
							'name' => __( 'Setting', 'astra' ),
			            ),
			            array(
							'type' 	=> 'checkbox',
							'id'	=> 'icons',
							'name' => __( 'Enable Icons', 'astra' ),
							'default' => ( isset( $instance['icons'] ) ) ? $instance['icons'] : '',
						),
						array(
							'type' 	=> 'checkbox',
							'id'	=> 'label',
							'name' => __( 'Enable Label', 'astra' ),
							'default' => ( isset( $instance['label'] ) ) ? $instance['label'] : '',
						),
						array(
							'type' 	=> 'checkbox',
							'id'	=> 'map',
							'class'	=> 'widget-address-map-wrap',
							'name' => __( 'Enable Map', 'astra' ),
							'default' => ( isset( $instance['map'] ) ) ? $instance['map'] : '',
						),
						array(
			                'id' => 'map_pos',
			                'type' => 'select',
			                'class'	=> 'widget-address-map-pos-wrap',
			                'name' => __( 'Map Position:', 'astra' ),
			                'default' => ( isset( $instance['map_pos'] ) && !empty( $instance['map_pos'] ) ) ? $instance['map_pos'] : 'top',
			                'options' => array(
			                    'top' 		=> __( "Top", 'astra' ),
			                    'bottom' 	=> __( "Bottom", 'astra' ),
			                )
			            ),
			            array(
			                'id' => 'text_align',
			                'type' => 'select',
			                'name' => __( 'Alignment:', 'astra' ),
			                'default' => ( isset( $instance['text_align'] ) && !empty( $instance['text_align'] ) ) ? $instance['text_align'] : 'left',
			                'options' => array(
			                    'left' 		=> __( "Left", 'astra' ),
			                    'center' 	=> __( "Center", 'astra' ),
			                    'right'		=> __( "Right", 'astra' ),
			                )
			            ),
					);

					//	Generate fields
					ast_generate_widget_fields( $this, $fields );
				?>

			</div>

		<?php
	}
	
}
