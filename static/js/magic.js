function _set_up_interface( cat ) {
	$('#loading').ajaxStart(function() { 
		$('.category').hide();
		$(this).show();
	}).ajaxStop(function() {
		$(this).hide();
	});
	__handle_json(null);
//			$.ajax(function() {
//				type:	"POST",
//				url:	"somefile.php",
//				data:	"var=1&name=blahblah",
//				dataType: "json",
//				success: function(json) {
//					__handle_json(json);
//				}
//			});
}

function __handle_json(json) {
	$('div.subheader').removeClass('subheader').addClass('question').after('<div class="subheader"><strong>Category: </strong>'+ cat);
	$('#left_box').html('Yes').addClass('green');
	$('#right_box').html('No').addClass('red');
}

$(document).ready(function() {
	$('#left_box').click(function() {
		_set_up_interface('fruits');
	});
	
	$('#right_box').click(function() {
		_set_up_interface('veggies');
	});
});