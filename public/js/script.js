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
        if($(this).prop("checked")) {
            var ku_trans_abstract = $("textarea#ku_trans_abstract");
            $("#summernote").summernote({
                height: ku_trans_abstract.outerHeight() - 50,
                focus: true,
                onKeyUp: function() {
                    ku_trans_abstract.html($('#summernote').code());
                }
            }).code(ku_trans_abstract.text());
            ku_trans_abstract.hide();
        }
        else {
            $("#summernote").destroy();
            $("#ku_trans_abstract").show();
        }
    });

    setTimeout(function() {
        $('.action-result').fadeOut(1000);
    }, 5000);
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