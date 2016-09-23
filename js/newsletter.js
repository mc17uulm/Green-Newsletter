/**
 * Created by Marco on 14.08.2016.
 */

jQuery(document).ready(function($) {

    var add = WPURLS.siteurl + "/wp-content/plugins/green-newsletter/lib/add.php";

    $('#submit_newsletter').click(function () {
        $("#errorNL").hide();
        $("#errorNL").text("");
        $("#successNL").hide();
        $("#successNL").text("");
        var proceed = true;
        $("#newsletter_form input[required=true]").each(function () {
            $(this).css('border-color', '');
            if (!$.trim($(this).val())) {
                $(this).css('border-color', 'red');
                proceed = false;
            }

            var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if ($(this).attr("type") == "email" && !email_reg.test($.trim($(this).val()))) {
                $(this).css('border-color', 'red');
                proceed = false;
            }
        });

        if (proceed) {
            $("#submit_newsletter").prop("disabled",true);
            post_data = {
                'prename': $('input[name=prename]').val(),
                'lastname': $('input[name=lastname]').val(),
                'email': $('input[name=email]').val()
            };
            $("#load").show();
            $.post(add, post_data, function (response) {
                if (response.type == 'error') {
                    $("#load").hide();
                    $("#submit_newsletter").prop("disabled",false);
                    $("#errorNL").show();
                    $("#errorNL").append("<p>Leider gab es einen Fehler:<br /> " + response.text + "<br /> Bitte versuchen Sie es erneut.</p>");
                } else {
                    $("#newsletter_form").hide();
                    $("#load").hide();
                    $("#successNL").show();
                    $("#successNL").append("<p><h5>Sie haben sich erfolgreich angemeldet!</h5><br /><a href='" +  WPURLS.siteurl + "/unsubscribe?key=" + response.text + "'>Abmelden</a></p>");
                }
            }, 'json');
        }
    });

    $("#newsletter_form input[required=true]").keyup(function () {
        $(this).css('border-color', '');
    });
});

function unsubscribe(key){
    jQuery(document).ready(function($) {
        $("#errorNL").hide();
        $("#errorNL").text("");
        $("#successNL").hide();
        $("#successNL").text("");
        $("#unsubscribe").prop("disabled", true);
        var url = WPURLS.siteurl + + "/wp-content/plugins/green-newsletter/lib/unsubscribe.php";
        post_data = {
            'key': key
        };
        $.post(url, post_data, function (response) {
            console.log("unsubscrition send");
            if (response.text != 'success') {
                $("#unsubscribe").prop("disabled", false);
                $("#errorNL").show();
                $("#errorNL").append("<p>Leider gab es einen Fehler!<br /> Bitte versuchen Sie es erneut.</p>");
                console.log("Err Output: " + response.text);
            } else {
                $("#successNL").show();
                $("#successNL").append("<p><h5>Sie haben sich erfolgreich abgemeldet!</h5></p>");
                console.log("Output: " + response.text);
            }
        }, 'json');
    });
}