/* Widget - Progress Bar */
jQuery(document).ready(function($) {

	/* jQuery - Progress Bars
	=================== */
	var progress = function(percent, element) {
		var progressBarWidth = percent * element.width() / 100;
		
		// With labels:
		// element.find('span').animate({ width: progressBarWidth }, 2500).html(percent + "%&nbsp;");
		
		// Without labels:
		element.find('span').animate({ width: progressBarWidth }, 2500);
	}
	
	
	jQuery('.ast-progress-val').each(function() {

		var bar = $(this);
		var max = ( $(this).attr('data-val') < 100 ) ? $(this).attr('data-val') : 100;

		// if( typeof ast_widgets != 'undefined' ) {
		// 	var bg_color = ast_widgets.theme_color || '';
		// 	bar.find('span').css('background-color', bg_color );
		// }

		progress(max, bar);
	});

});

/* Widget - Newslater */
jQuery(document).ready(function( $ ){

	function isValidEmailAddress(emailAddress) {
	    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	    return pattern.test(emailAddress);
	};

	function validate_it( current_ele, value ) {
		if( !value.trim() ) {
			return false;
		} else if( current_ele.hasClass('ast-input-email') ) {
			if( isValidEmailAddress( value ) ) {
				return true;
			}
			else {
				return false;
			}
		} else if( current_ele.hasClass('ast-input-name') ) {
			if( /^[a-zA-Z0-9- ]*$/.test( value ) == false ) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	};
	
	$('.widget.ast_newsletter').each(function(index, el) {
		var widget 	= $(this),
			form 	= widget.find('form'),
			submit  = form.find('.ast-button.subscribe');


	
	    // Submit Add Subscriber Request
	    form.find('.ast-button.subscribe').click(function(e){
			var $this	= $(this),
				form 	= $this.parents("form"),
				submit_status = true,
				data 	= form.serialize(),
				form_input 	= form.find('.ast-newsletter-form-input'),
				form_success = form.find('.ast-success-msg'),
				form_error	= form.find('.ast-error-msg');

			form.find('.ast-input').each( function(index) {
				
				var $this = $(this);

				if( ! $this.hasClass('ast-button')) { // Check condition for Submit Button
					var	input_name = $this.attr('name'),
						input_value = $this.val();

					var input_required = $this.attr('required') ? true : false;

					if( input_required ) {
						if( validate_it( $this, input_value ) ) {
							$this.removeClass('ast-input-error');
							// input_status.push([input_name,false]);
						} else {
							submit_status = false;
							$this.addClass('ast-input-error');
							// input_status.push([input_name,true]);
						}
					}
				}
			});

			if ( submit_status ) {
				jQuery.ajax({
					url: ast_cpwidget_ajax.url,
					data: data,
					type: 'POST',
					dataType: 'HTML',
					
					success: function(result){

						var obj 	= $.parseJSON( result ),
							status 	= '';
						
						if( typeof obj.status != 'undefined' && obj.status != null ) {
							status = obj.status;
						}

						if ( status == 'success' ) {
							form_input.hide();
							form_success.show();
						}else{
							form_input.hide();
							form_error.show();
						}
					},
					error: function( data ){
						form_input.hide();
						form_error.show();
			        }
				});
			};
			e.preventDefault();
		});
		
		// Go Back Error Message
		form.find('.ast-go-back').click(function(e) {
			e.preventDefault();
			var form 	= $(this).parents("form"),
				form_input 	= form.find('.ast-newsletter-form-input'),
				form_error	= form.find('.ast-error-msg');

				form_input.show();
				form_error.hide();
		});
	});
});
