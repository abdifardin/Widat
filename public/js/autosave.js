window.setInterval(event, 5000);
var previous_val = $("#ku_trans_abstract").val();
var save_changes_label = $("#save_changes_label").html();
var current_val;

$('#retrieve_drafts_label').on('click', function (e) {
	e.preventDefault();
	$(this).hide();
	var retrieve = 1;
	var _token = $('input[name=_token]').val();
	$.post(window.location.href, {retrieve: retrieve, _token: _token}, function(result, status){
		if(result != 'empty'){
			$('#ku_trans_abstract').val(result);
		}
	});
});
	
function event() {
	var current_val = $("#ku_trans_abstract").val();
	if (current_val.localeCompare(previous_val) != 0 && document.getElementById("ku_trans_abstract").value != '') {
		$("#save_changes_label").show();
		$("#retrieve_drafts_label").hide();
		var ku_trans_abstract = $("#ku_trans_abstract").val();
		var autosave = 1;
		var _token = $('input[name=_token]').val();
		$.post(window.location.href, {ku_trans_abstract: ku_trans_abstract, autosave: autosave, _token: _token}, function(result, status){
			$("#save_changes_label").hide();
			$("#retrieve_drafts_label").show();
		});
	}
	previous_val = current_val;
}