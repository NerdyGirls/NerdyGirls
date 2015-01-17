(function($) {
	"use strict";

	var iconKeyUpTimeout = null;

	$('.wpb_edit_form_elements .vcex_icon_field').each(function() {
		var paramValue = $(this).val();
		var paramName = $(this).data('param-name');
		$(this).prev().html('<span class="fa fa-' + paramValue + '"></span>');
	}).on('keyup', function() {
		var paramValue = $(this).val();
		var currentIcon = '.fa-' + paramValue;
		$(this).prev().html('<span class="fa fa-' + paramValue + '"></span>');
		$(this).next('.wpb_edit_form_elements .vcex_icon_field ~ .vcex-font-awesome-icon-select-window').children('.fa').removeClass('active');
		$(this).next('.wpb_edit_form_elements .vcex_icon_field ~ .vcex-font-awesome-icon-select-window').find($(currentIcon)).addClass('active');
		if ( iconKeyUpTimeout != null ) {
			clearTimeout( iconKeyUpTimeout );
			iconKeyUpTimeout = null;
		}
	});

	$('.wpb_edit_form_elements .vcex_icon_field ~ .vcex-font-awesome-icon-select-window').on('click', '.fa', function() {
		var $field = $(this).parents('.vcex-font-awesome-icon-select-window').parent().find('input');
		$('.wpb_edit_form_elements .vcex_icon_field ~ .vcex-font-awesome-icon-select-window .fa').removeClass('active');
		if ( $(this).data('name') == 'clear' ) {
			$field.val('').prev().html('');
		} else {
			$(this).addClass('active');
			$field.val($(this).data('name')).prev().html('<span class="fa fa-' + $field.val() + '"></span>');
		}
	});

	$('.wpb_edit_form_elements .vcex-font-awesome-icon-filter').change(function() {
		var $field = $(this).parent().find('input');
		if ( $(this).val() == '' || $(this).data('name') == 'clear' ) {
			// nothing
		} else if ( $(this).val() == 'all' ) {
			$field.val('').trigger('keyup');
		} else {
			$field.val($(this).val()).trigger('keyup');
		}
	});

})(jQuery);