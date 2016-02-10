var sentencePos = 0;
var disallow_keystroke_register = false;
var translation_save_clicked = false;

$(function() {
    $('#search-form').submit(function() {
        suggestions();
        return false;
    });

    $(document).click(hideSuggestions);

    $('.toggle-down-sibling').click(function() {
        var $sibling = $(this).parent().siblings();
        $sibling.toggleClass('hidden');
    });

    $('.toggle-password')
        .mousedown(showPassword)
        .mouseup(hidePassword)
        .mouseleave(hidePassword);

    $('a.delete-user').click(function() {
        $('.delete-user-modal').modal('show');
        var user_id = $(this).children('input[name=user_id]').val();
        $('.delete-user-modal input[name=user_id]').val(user_id);
    });

    $("textarea#ku_trans_abstract").change(updateTranslationScore).keyup(updateTranslationScore);
    updateTranslationScore();

    setTimeout(function() {
        $('.action-result').fadeOut(1000);
    }, 5000);


    $('.beg-sentence').click(function() {
        sentencePos = 0;
        highlightEnAbstract();
    });
    $('.rewind-sentence').click(function() {
        sentencePos = (sentencePos > 0) ? sentencePos - 1 : sentencePos;
        highlightEnAbstract();
    });
    $('.forward-sentence').click(function() {
        sentencePos++;
        highlightEnAbstract();
    });
    highlightEnAbstract();

    $('.register-keystroke').keyup(function() {
        registerKeystroke();
    });

    //setTimeout(registerActivity, 1000);
    //setTimeout(refreshStatuses, 1000);
	
	$(window).on('beforeunload', function() {
		if($('#have_draft').val() && !translation_save_clicked){
			return 'You have draft. To save it stay on this page and after retrieve draft click on save changes.';
		}
		if (checkForUnsavedChanges() != 0 && checkForUnsavedChanges() != null &&
            !translation_save_clicked) {
            return 'You have unsaved changes!';
        }
    });
	
    $('#inspection').click(function() {
        var ku_trans_title = $("#ku_trans_title").val();
        var ku_trans_abstract = $("#ku_trans_abstract").val();
        var en_abstract = $("#hidden-en-abstract").val();
		if(ku_trans_abstract.length / en_abstract.length > 0.79){
			return confirm('Do you want to submit this article for proofreading? You can still make changes whilst it is waiting for proof read.');
		}else{
			return confirm('This looks like shorter than English text, are you sure you want to send it for proofreading?');
		}
    });
	
	$("button[name = 'save']").click(function() {
		translation_save_clicked = true;
	});

    $('.peek-link').click(peek);

    refreshCsrf();

    $('.delete-topic-form').submit(function() {
        return confirm("Are you sure?");
    });
});

function suggestions()
{
    var url = $('#search-form').prop('action');
    var token = $('input[name=_token]').val();
    var title = $('#topic-peek-search').val();
    $('div.suggestions').removeClass('hidden');
    var listGroup = $('.suggestions .list-group');
    listGroup.html('<span class="fa fa-cog fa-spin"></span>');
    $.ajax({
        type: "POST",
        url: url,
        data: {topic: title, _token: token},
        success: function(data) {
            var topics = data.topics;

            if(!topics) {
                listGroup.html('<p class="text-danger text-center">Error!</div>');
            }
            else if(!topics.length) {
                listGroup.html('<p class="text-danger text-center">No Hits Found</div>');
            }
            else {
                var items = "";
                for(var i = 0; i < topics.length; i++) {
                    items += "<a href='javascript:;' class='list-group-item peek-link'>" + topics[i] + "</a>";
                }
                listGroup.html(items);
                $('.peek-link').click(peek);
            }
        }
    });
}

function hideSuggestions()
{
    $('.suggestions .list-group').html("");
    $('.suggestions').addClass("hidden");
}

function peek()
{
    $('.refering-to-topic').hide();
	$('#peek-no-ku-trans').addClass('hidden');
	var url = $('#peek-form').prop('action');
    var token = $('input[name=_token]').val();
    var title = $(this).find(".peek_topic_title_box").html();
	if(typeof title == 'undefined'){
		title = $(this).text();
	}
    $('.topic-peek-modal').modal('show');
    $('.topic-peek-modal div.loader').removeClass('hidden');
    $('.topic-peek-modal .topic-not-found').addClass('hidden');
    $('.topic-peek-modal .translation-group.en').addClass('hidden');
    $('.topic-peek-modal .translation-group.ku').addClass('hidden');
    $.ajax({
        type: "POST",
        url: url,
        data: {topic: title, _token: token},
        success: function(data) {
			$('.topic-peek-modal div.loader').addClass('hidden');
            if(data.error) {
                $('.topic-peek-modal .topic-not-found').removeClass('hidden');
            }
            else {
                $('.topic-peek-modal .translation-group.en').removeClass('hidden');
                $('#peek-en-title').html(data.topic);
                $('#peek-en-abstract').html(data.abstract);
				
                if(data.ku_topic) {
                    $('#peek-no-ku-trans').addClass('hidden');
					$('.topic-peek-modal .translation-group.ku').removeClass('hidden');
                    $('#peek-ku-title').html(data.ku_topic);
                    $('#peek-ku-abstract').html(data.ku_abstract);
                }
                else {
                    $('#peek-no-ku-trans').removeClass('hidden');
                    $('a.btn.translate-now').prop('href', data.translate_url);
					$('#deletion-rec-box').html(data.delete_recomend);
                }
				$('.refering-to-topic').show();
				$('.refering-to-topic').attr('href', data.translate_url);
            }
        }
    });
}

function showPassword()
{
    $(this).children('span.fa').removeClass('fa-eye-slash').addClass('fa-eye');
    var $input = $(this).siblings('input');
    var rep = $("<input type='text' />")
        .attr("id", $input.attr("id"))
        .attr("name", $input.attr("name"))
        .attr('class', $input.attr('class'))
        .val($input.val())
        .insertBefore($input);
    $input.remove();
}

function hidePassword()
{
    $(this).children('span.fa').removeClass('fa-eye').addClass('fa-eye-slash');
    var $input = $(this).siblings('input');
    var rep = $("<input type='password' />")
        .attr("id", $input.attr("id"))
        .attr("name", $input.attr("name"))
        .attr('class', $input.attr('class'))
        .val($input.val())
        .insertBefore($input);
    $input.remove();
}

function highlightEnAbstract()
{
    if(!$("#hidden-en-abstract").length) {
        return;
    }

    var abstract = $("#hidden-en-abstract").val().trim().replace(/\.(?!\d)/g,'.|').split("|");
    if(sentencePos >= abstract.length) {
        sentencePos = abstract.length;
    }
    var done = "";
    var current = "";
    var next = "";


    for(var i = 0; i < sentencePos; i++) {
        if(abstract[i])
            done += abstract[i];
    }
    if(done.length) {
        done = "<span class='text-success'>" + done + "</span>";
    }

    if(abstract[sentencePos])
        current += abstract[sentencePos];
    if(current.length) {
        current = "<span class='text-primary'>" + current + "</span>";
    }

    for(var i = sentencePos + 1; i < abstract.length; i++) {
        if(abstract[i])
            next += abstract[i];
    }
    if(next.length) {
        next = "<span class='next-sentences'>" + next + "</span>";
    }

    $("p#en-abstract").html(done + current + next);
}

function updateTranslationScore()
{
    var abstract = $('textarea#ku_trans_abstract');
    if(!abstract.length) {
        return;
    }
    var plaintext = abstract.val().trim();
    var words = plaintext.split(" ");
    var wordcount = 0;
    for(var i = 0; i < words.length; i++) {
        if(words[i].trim().length > 1) {
            wordcount++;
        }
    }

    wordcount -= $('#current_score').val();

    $('button[name=save] span.badge').html(wordcount > 0 ? "+" + wordcount : wordcount);
}

function checkForUnsavedChanges()
{
    var abstract = $('textarea#ku_trans_abstract');
	
    if(!abstract.length) {
        return null;
    }
    var plaintext = abstract.val().trim();
    var words = plaintext.split(" ");
    var wordcount = 0;
    for(var i = 0; i < words.length; i++) {
        if(words[i].trim().length > 1) {
            wordcount++;
        }
    }

    wordcount -= $('#current_score').val();
    return wordcount;
}

function registerActivity()
{
    var url = $('#reg-activity-url').val();
    $.get(url, function() {
        setTimeout(registerActivity, 1000);
    });
}

function registerKeystroke()
{
    if(disallow_keystroke_register) {
        return;
    }
    var url = $('#reg-keystroke-url').val();
    $.get(url);
    disallow_keystroke_register = true;
    setTimeout(function() {
        disallow_keystroke_register = false;
    }, 1000);
}

function refreshStatuses() {
    var statusUrl = $('#get-statuses-url').val();

    $.get(statusUrl, function(data) {
        for(var i = 0; i < data.length; i++) {
            if(data[i].typing == "typing") {
                $('#typing-status-' + data[i].id).removeClass('hidden');
            }
            else {
                $('#typing-status-' + data[i].id).addClass('hidden');
            }
            if(data[i].online == "online") {
                $('#online-status-' + data[i].id).removeClass('offline');
                $('#online-status-' + data[i].id).addClass('online');
            }
            else {
                $('#online-status-' + data[i].id).removeClass('online');
                $('#online-status-' + data[i].id).addClass('offline');
            }
        }
        setTimeout(refreshStatuses, 1000);
    });
}

function refreshCsrf()
{
    var url = '/csrf';
    $.get(url, function(data) {
        $('input[name=_token]').val(data.csrf);
        $('meta[name=csrf-token]').attr('content', data.csrf);
        setTimeout(refreshCsrf, 1000 * 60);
    });
}

$(document).ready(function(){
	$("[name='ku_trans_title']").keypress(function(event){
		if (event.keyCode == 10 || event.keyCode == 13){
			event.preventDefault();
			$("#ku_trans_abstract").focus();
		}
	});
	$("#inspection_edit_key").click(function(event){
		event.preventDefault();
		$("#inspection").hide();
		$("#inspection_edit").show();
	});
	$('#bulk_restore_all').change(function(){
		if (this.checked) {
			$('.bulk_restore').prop('checked', true);
		}else{
			$('.bulk_restore').prop('checked', false);
		}
	});
});