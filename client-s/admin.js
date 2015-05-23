/**
 * Dashboard JS for the plugin
 */
(function($) {
	$(document).ready(
		function() {
			var colorpickers = $('.colorpicker');

			colorpickers.colpick(
				{
					layout     : 'hex',
					submit     : 0,
					colorScheme: 'dark',
					onChange   : function(hsb, hex, rgb, el, bySetColor) {
						$(el).css('border-color', '#' + hex.toUpperCase());
						// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
						if(!bySetColor) $(el).val('#' + hex.toUpperCase());
					}
				}).keyup(
				function() {
					$(this).colpickSetColor(this.value);
				});

			colorpickers.each(
				function() {
					$(this).colpickSetColor(this.value);
				});
		});
})(jQuery);