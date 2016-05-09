if($("#ku_trans_abstract").length){
	window.setInterval(event, 5000);
	var previous_val = $("#ku_trans_abstract").val();
	var previous_title = $("#ku_trans_title").val();
	var save_changes_label = $("#save_changes_label").html();
	var current_val;
	var current_title;
	//var autosave_status = false;
	var retrieve_status = false;
	var first_time = true;
	var have_draft = $("#have_draft").val();

	$('#retrieve_drafts_label').on('click', function (e) {
		e.preventDefault();
		$(this).hide();
		var retrieve = 1;
		var _token = $('input[name=_token]').val();
		$.post(window.location.href, {retrieve: retrieve, _token: _token}, function(result, status){
			if(result != 'empty'){
				$('textarea#ku_trans_abstract').summernote('code', result.abstract);
				$('#ku_trans_title').val(result.topic);
				updateTranslationScore();
			}
		});
	});
		
	function event() {
		var current_val = $("#ku_trans_abstract").val();
		var current_title = $("#ku_trans_title").val();
		
		if ((current_val.localeCompare(previous_val) != 0 && current_val.localeCompare('<p><br></p>') != 0 && document.getElementById("ku_trans_abstract").value != '')
		|| (current_title.localeCompare(previous_title) != 0 && document.getElementById("ku_trans_title").value != '')) {
			
			if(first_time){
				if(have_draft){
					retrieve_status = confirm("Do you want to retrieve your draft or not?");
				}
				
				if(retrieve_status){
					var retrieve = 1;
					var _token = $('input[name=_token]').val();
					$.post(window.location.href, {retrieve: retrieve, _token: _token}, function(result, status){
						if(result != 'empty'){
							$('textarea#ku_trans_abstract').summernote('code', result.abstract);
							$('#ku_trans_title').val(result.topic);
							updateTranslationScore();
						}
					});
				}
				first_time = false;
			}
			
			//if(autosave_status){
				$("#save_changes_label").show();
				$("#retrieve_drafts_label").html('<a href="">Draft saved 5 seconds ago, click here to retrieve and click "Save changes".</a>');
				$("#retrieve_drafts_label").hide();
				var ku_trans_topic = $("#ku_trans_title").val();
				var ku_trans_abstract = $("#ku_trans_abstract").val();
				var autosave = 1;
				var _token = $('input[name=_token]').val();
				$.post(window.location.href, {ku_trans_topic: ku_trans_topic, ku_trans_abstract: ku_trans_abstract, autosave: autosave, _token: _token}, function(result, status){
					$("#save_changes_label").hide();
					$("#retrieve_drafts_label").show();
				});
			//}
		}
		previous_val = current_val;
		previous_title = current_title;
	}
}