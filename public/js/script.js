var sentencePos = 0;

$(function() {
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

    $("#use-rich-format").change(function() {
        var summernote = $("#summernote");
        var ku_trans_abstract = $("textarea#ku_trans_abstract");
        if($(this).prop("checked")) {
            summernote.show();
            summernote.summernote({
                height: ku_trans_abstract.outerHeight() - 50,
                focus: true,
                onChange: function() {
                    ku_trans_abstract.val(summernote.code());
                }
            }).code(ku_trans_abstract.val());
            ku_trans_abstract.hide();
        }
        else {
            summernote.destroy();
            ku_trans_abstract.show();
            summernote.hide();
        }
    });

    $("textarea#ku_trans_abstract").change(updateTranslationScore).keyup(updateTranslationScore);

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
});

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
    var plaintext = $('textarea#ku_trans_abstract').val().replace(/<\/?[^>]+(>|$)/g, "");
    var wordcount = plaintext.split(" ").length;
    $('button[name=save] span.badge').html(wordcount > 0 ? "+" + wordcount : "0");
}