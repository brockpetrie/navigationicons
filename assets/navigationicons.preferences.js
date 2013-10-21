jQuery(document).ready(function($) {
	$('.navigationicons-duplicator').symphonyDuplicator({
		orderable: false,
		collapsible: false,
		constructable: false,
		destructable: false
	}).on('input blur keyup', '.instance input.navIcon', function(event) {
		var label = $(this),
			value = label.val();

		label.parents('.instance').find('span').attr('data-icon', value);
	});
});
