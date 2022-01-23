(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( document ).ready(function() {

		var results = window.location.search;
		console.log("Parametros: ", results);
	 
		$(".btn-jogar a").attr('href', function(i, h) {
			return h + (h.indexOf('?') != -1 ? "&ajax=1" : results);
		});
		$("a.btn.btn--green.btn--full.d-none").attr('href', function(i, h) {
			return h + (h.indexOf('?') != -1 ? "&ajax=1" : results);
		});
		$("a.d-none.d-lg-block.logo-boxed").attr('href', function(i, h) {
			return h + results;
		});

	});

})( jQuery );
