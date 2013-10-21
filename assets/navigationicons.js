jQuery(document).ready(function($) {

	$.each(navigationArr, function(key, v) {
		if (key == '') return false;
		$('#nav > ul > li:contains('+ key +')').each(function() {
			var txt = $(this).clone().children().remove().end().text();
			if ($.trim(txt) === key) $(this).attr('data-icon', v);

			if ($.trim($(this).children('a').text()) === key) $(this).children('a').attr('data-icon', v);
		});
	});

});