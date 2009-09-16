function _set_up_interface( cat ) {
	$('#loading').ajaxStart(function() { 
		$('.category').hide();
		$(this).show();
	}).ajaxStop(function() {
		$(this).hide();
	});
	
//			$.ajax(function() {
//				type:	"POST",
//				url:	"somefile.php",
//				data:	"var=1&name=blahblah",
//				dataType: "json",
//				success: function(json) {
//					__handle_json(json);
//				}
//			});
	__handle_json(null, cat);
}

function __handle_json(json, cat) {
	$('div.subheader').removeClass('subheader').addClass('chosencat').html('<strong>Category: </strong>'+ cat);
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