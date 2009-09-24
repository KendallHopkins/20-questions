function _set_up_interface( cat ) {
	$('#categories').hide();
	$('.subheader').removeClass().html('').addClass('chosencat').html('<strong>Category:</strong> ' + cat);
	$('.response').show();

}

//first question, inputs are null, since there is no questino to answer
//after that, you will set them
function _get_question( question_id, response ) { 
	var post_data = { game_id: $.fn.twentyquestions.game_id };
	//add question to post_data
	
	//question_id and response are input of this function, that should be overloaded to allow null
	if( ( question_id !== null ) && ( response !== null ) ) {
		post_data.question_id = question_id;
		post_data.response = Number( response );
	}
	
	$.ajax({
		type:	"POST",
		url:	"/action/question",
		data:	post_data,
		dataType: "json",
		success: function(json) {
			__handle_question(json);
		}
	});
}

function __submit_answer_confirmation( answer_item_id, alternate ) {
	var post_data = { game_id: $.fn.twentyquestions.game_id, item_id: answer_item_id };
	$.ajax({
		type:	"POST",
		url:	"/action/game_end",
		data:	post_data,
		dataType: "json",
		success: function(json) {
			alternate ? __handle_game_end({success:false}):__handle_game_end(json);
		}
	});
}

function __handle_new_game(json) {
	console.log(json);
	if(json.success) {
		$.fn.twentyquestions.game_id = json.game_id;
		_get_question( null, null );
	} else {
		__throw_error(json);
	}
}

function __handle_game_end(json) {
	if(json.success) {
		$('.response').unbind().hide();
		$('.question_div, #questions, #answer_submission').hide();
		$('#answer').show().html('Haha, so I\'m right again scrub!! You can\'t handle my skillz, can you has skills like me? no you can\'t has skills like me. You are FAIL!! <br /> <h3><a href="/index">Back Home</a></h3>');
		
	} else { // game ended with a submission of a word to the game...clean up, say thanks and redirect in 5 seconds
		$('#answer_submission').hide().html('<h3>Thanks for playing, we got your submission</h3><br /><h6>I\'m still better than you haha scrubber</h6><br /><h3>You will be directed home in 5 seconds</h3>').show();
		
		setTimeout("location.href='/index';", 5000);
	}
}

function __get_top_10() {
	var post_data = { game_id: $.fn.twentyquestions.game_id };
	$.ajax({
		type:	"POST",
		url:	"/action/item_list",
		data:	post_data,
		dataType: "json",
		success: function(json) {
			__display_top_10(json);
		}
	});
}

function __display_top_10(json) {
	var answers = json.answer_array;
	for( var i in answers ) {
		$('ol.top10').append('<li id="'+ answers[i].id + '"><a href="#">' + answers[i].name + '</a></li>');
	}
	$('ol.top10 li a').click(function() {
		__submit_answer_confirmation( $(this).parent().attr('id'), true );
		return false; //prevents the following of href...
	});
}

function __get_answer_search_results( key ) {
	var post_data = { game_id: $.fn.twentyquestions.game_id, search: key };
	$.ajax({
		type:	"POST",
		url:	"/action/item_search",
		data:	post_data,
		dataType: "json",
		success: function(json) {
			__display_answer_search_result_list(json);
		}
	});
}

function __display_answer_search_result_list(json) {
	var items = json.item_array;
	if(items.length == 0) {
		$('ul.possible_word_list').html('<li>We don\'t have this word...you can add it below.</li>');
	} else {
		$('ul.possible_word_list').html('');
		for( var i in items ) {
			$('ul.possible_word_list').append('<li id="'+ items[i].id + '"><a href="#">' + items[i].name + '</a></li>');
		}
	}
	$('ul.possible_word_list li a').click(function() {
		__submit_answer_confirmation( $(this).parent().attr('id'), true );
		return false; //prevents the following of href...
	});
}

function __add_answer( answer ) {
	var post_data = { group_id: $.fn.twentyquestions.group_id, name: answer };
	$.ajax({
		type:	"POST",
		url:	"/action/item_add",
		data:	post_data,
		dataType: "json",
		success: function(json) {
			__submit_answer_confirmation( json.id, true );
		}
	});
}

function __handle_question( json ) {
	console.log( json );
	if( json.success ) {
		$.fn.twentyquestions.question_count = json.count;
		if( json.type != 'final' ) {
			$.fn.twentyquestions.current_question_id = json.question.id;
			$.fn.twentyquestions.current_question_name = json.question.name;
			$('.question_div').html('<strong>' + (json.count + 1) + '.</strong> ' + json.question.name);
		} else {
			$.fn.twentyquestions.answer_id = json.answer.id;
			$.fn.twentyquestions.answer_name = json.answer.name;
			//handle final question
			// later the use of json.answer.id will be required for the suggestions, selection,etc...
			//we also need it when asking if this was the correct answer.
			//if the user says it was, then you do another ajax call to confirm
			$('.question_div').html('Were you thinking of a(n) ' + $.fn.twentyquestions.answer_name + '?');
			$('.green').unbind().click(function() {
				__submit_answer_confirmation( $.fn.twentyquestions.answer_id, false );
			});
			

			$('.red').unbind().click(function() {
				$('.response').unbind().hide();
				$('.question_div, #questions').hide();
				$('#answer_submission').show();
				__get_top_10();
				$('#search_answer').keyup(function() {
					if($.trim($(this).val()) != 0)
						__get_answer_search_results( $(this).val() );
				});
				
				$('#add_answer_button').click(function() {
					if($('#add_answer').val() !== '') {
						$(this).removeAttr('disabled').attr('disabled','disabled');
						__add_answer( $('#add_answer').val() );
					}
				});
			});

			$('#questions ul').prepend('<li class="'+ ( ($.fn.twentyquestions.question_count % 2) == 0 ? 'even':'odd')+ '"><div class="question"><div class="q_index">'+ ($.fn.twentyquestions.question_count + 1) +'</div><div class="q_text">' + $.fn.twentyquestions.current_question_name + '</div><div class="q_usr_resp">You said <strong class="'+ (response ? 'g':'r') + '">' + (response ? 'Yes':'No') + '</strong></div><div class="clear"></div></div></li>');
		}
	} else {
		__throw_error(json);
	}
}

function __choose_group( group_id ) {
	$.fn.twentyquestions.group_id = group_id;
	$.ajax({
		type:	"POST",
		url:	"/action/game_new",
		data:	"group_id=" + group_id,
		dataType: "json",
		success: function(json) {
			__handle_new_game(json);
		}
	});
}

function __handle_response( response ) {
	_get_question( $.fn.twentyquestions.current_question_id, response );
}

function __throw_error(json) {
	// FOR NOW REDIRECT TO HOMEPAGE
	alert('YOU ARE SERIOUSLY MADE OF FAIL! AND IT\'S PROBABLY KENDALL\'S FAULT<br/>Here is the error: ' + json.error);
	//window.location = '/index';
}

$(document).ready(function() {
	
	$.fn.twentyquestions = {
		game_id: 0,
		question_count: 0,
		current_question_id: null,
		current_question_name: null,
		max_questions: null
	};
	
	$('.category').click(function() {
		__choose_group( parseInt($(this).attr('id'), 10) );
		_set_up_interface( $(this).html() );
	});
	
	$('.green').click(function() {
		__handle_response( true );
	});
	
	$('.red').click(function() {
		__handle_response( false );
	});
});