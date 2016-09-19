/**
 * Created by Marco on 14.08.2016.
 */

jQuery(document).ready(function($) {

    var add = window.location.protocol + "//" + window.location.host + "/wp-content/plugins/green-newsletter/lib/add.php";

    $('#mc-embedded-subscribe').click(function () {
        $("#errorNL").hide();
        $("#errorNL").text("");
        $("#successNL").hide();
        $("#successNL").text("");
        var proceed = true;
        $("#mce-EMAIL").each(function () {
            $("#mce-EMAIL").css('border-color', '');
            if (!$.trim($("#mce-EMAIL").val())) {
                $("#mce-EMAIL").css('border-color', 'red');
                proceed = false;
            }
        });

        var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if ($("#mce-EMAIL").attr("type") == "email" && !email_reg.test($.trim($("#mce-EMAIL").val()))) {
            $("#mce-EMAIL").css('border-color', 'red');
            proceed = false;
        }

        if (proceed) {
            $("#mc-embedded-subscribe").prop("disabled",true);
            post_data = {
                'email': $('#mce-EMAIL').val()
            };
            $.post(url, post_data, function (response) {
                if (response.type == 'error') {
                    $("#mc-embedded-subscribe").prop("disabled",false);
                    $("#errorNL").show();
                    $("#errorNL").append("<p>Leider gab es einen Fehler:<br /> " + response.text + "<br /> Bitte versuchen Sie es erneut.</p>");
                } else {
                    $("#newsletter_form").hide();
                    $("#successNL").show();
                    $("#successNL").append("<p><h5>Sie haben sich erfolgreich angemeldet!</h5><br /><a href='http://gruene-heidenheim.de/unsubscribe?key=" + response.text + "'>Abmelden</a></p>");
                }
            }, 'json');
        }
    });
});