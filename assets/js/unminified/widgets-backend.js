
/**
 * 	Widget Helper functions
 */

/**
 *	Field - Repeater
 */
//	Add
jQuery(document).on('click', '.controls .add', function(event) {
	jQuery(this).closest('.repeater').clone().appendTo(".fl-builder-widget-settings .repeaters, .widget.open .repeaters, .customize-control.expanded .widget .repeaters");
	var inputField = jQuery(this).closest('.ast-widget').find('.ast-widget-field-text input').first();

	if( 'undefined' != typeof inputField ) {
		inputField.trigger('change');
	}
});
//	Remove
jQuery(document).on('click', '.controls .remove', function(event) {
	var ind = jQuery('.fl-builder-widget-settings .repeaters .repeater:last, .widget.open .repeaters .repeater:last, .customize-control.expanded .widget .repeaters .repeater:last').index();
		if( ind > 0 ) {
			//	Translation Ready Message
			if( confirm( ast_widgets.repeater_msg_confirm ) ) {
				
				var inputField = jQuery(this).closest('.ast-widget').find('.ast-widget-field-text input').first();
				jQuery(this).closest('.repeater').remove();
				
				if( 'undefined' != typeof inputField ) {
					inputField.trigger('change');
				}
			}
		} else {
			alert( ast_widgets.repeater_msg_error );
		}
});

/**
 *	Field - Image ( Add )
 */
jQuery(document).on('click', '.ast-select-image', function(event) {

	var self 	= jQuery(this);
	var parent 	= self.parents('.widget-content, .fl-builder-widget-settings');

	var frame = wp.media({
		title: 'Select or Upload Image',
		button: {
			text: 'Choose Image'
		},
		library: {
			type: 'image'
		},
		multiple: false,
	});

	// Handle results from media manager.
	frame.on('close',function( ) {
		var attachments = frame.state().get('selection').toJSON();

		var image_preview_url = attachments[0].url;

		if( parent.find('.ast-remove-image').length > 0 ) {
			parent.find('.ast-field-image-preview img').attr('src', image_preview_url );
		} else {
			var img_html = '<div class="ast-field-image-preview-inner">'
					+ '		<span class="ast-remove-image button">X</span>'
					+ '		<img src="' + image_preview_url + '" />'
					+ '</div>';

			parent.find('.ast-field-image-preview').append( img_html );
		}
		parent.find('.ast-image-url').val( attachments[0].url );
		parent.find('.ast-image-alt').val( attachments[0].alt );
		parent.find('.ast-image-title').val( attachments[0].title );

		var data = 	'id:' + attachments[0].id + '^'
				+	'url:' + attachments[0].url + '^'
				+	'alt:' + attachments[0].alt + '^'
				+	'title:' + attachments[0].title;

		parent.find('.ast-field-image-meta').val( data );

		parent.find('.ast-image-size-select, .ast-image-width').show();

		var inputField = self.closest('.ast-widget').find('.ast-widget-field-text input').first();
		if( 'undefined' != typeof inputField ) {
			inputField.trigger('change');
		}
	});

	frame.open();
	return false;
});

/**
 *	Field - Image ( Remove )
 */
jQuery(document).on('click', '.ast-remove-image', function(event) {

	if( confirm('Do you want to remove this image?') ) {
		var self 	= jQuery(this);
		var inputField = self.closest('.ast-widget').find('.ast-widget-field-text input').first();
		var parent 	= self.parents('.widget-content, .fl-builder-widget-settings');
		parent.find('.ast-field-image-preview').html('');
		parent.find('.ast-field-image-preview img').attr('src', '' );
		parent.find('.ast-field-image-meta').val( '' );
		parent.find('.ast-image-url').val( '' );
		parent.find('.ast-image-alt').val( '' );
		parent.find('.ast-image-title').val( '' );
		parent.find('.ast-image-size-select, .ast-image-width').hide();

		if( 'undefined' != typeof inputField ) {
			inputField.trigger('change');
		}
	}
});
/**
 *	Field - Image ( Process )
 */
jQuery(document).on('widget-updated widget-added', function(){
	ast_process_image_uploader();
});
jQuery(document).ready(function($) {
	ast_process_image_uploader();
});
function ast_process_image_uploader() {
	jQuery('.ast-field-image').each(function(index, el) {
		var parent 	= jQuery(el).parents('.widget-content, .fl-builder-widget-settings');
		var val 	= parent.find('.ast-field-image-meta').val();
		if( val == '' ) {
			parent.find('.ast-image-size-select, .ast-image-width').hide();
		} else {
			//	Not found image?
			parent.find('.ast-image-size-select, .ast-image-width').show();
		}
	});
}

/**
 *	Field - ColorPicker
 */
jQuery(document).on('widget-updated widget-added', function(){
    ast_widgets_field_colorpicker();
});
jQuery(document).ready(function($) { 
	ast_widgets_field_colorpicker();
});
function ast_widgets_field_colorpicker() {
	
	// Check 'wpColorPicker' function exist
	if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
		jQuery('#widgets-right .ast-widgets-field-colorpicker').wpColorPicker();
	}
}

/**
 * Address Widget Dependency Script
 */
jQuery(document).ready(function(){

	updateWidgetMapPosition();

	jQuery(document).on( 'widget-added', updateWidgetMapPosition );

	jQuery(document).on( 'widget-updated', updateWidgetMapPosition );

	jQuery(document).on( 'change', '.widget-address-map-wrap input[type=checkbox]', function() {
		if( jQuery(this).attr('checked') == 'checked' ) {
			jQuery(this).parent().next('.widget-address-map-pos-wrap').show();
		} else {
			jQuery(this).parent().next('.widget-address-map-pos-wrap').hide();
		}
	});

	function updateWidgetMapPosition() {
		jQuery(document).find( '.widget-address-map-wrap input[type=checkbox]').each( function( i, e ) {
			if( jQuery(this).attr('checked') == 'checked' ) {
				jQuery(this).parent().next('.widget-address-map-pos-wrap').show();
			} else {
				jQuery(this).parent().next('.widget-address-map-pos-wrap').hide();
			}
		});
	}
});