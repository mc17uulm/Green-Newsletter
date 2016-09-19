/**
 * Created by Marco on 16.09.2016.
 */

jQuery(document).ready(function($) {

    // URL zur PHP File durch welche der API-Key auf Gültigkeit überprüft wird
    var url = WPURLS.siteurl + "/wp-content/plugins/green-newsletter/lib/checkApi.php";

    console.log("URL: " + WPURLS.siteurl);

    /**
     * Sollte im Input field für den API-Key  eine Änderung stattfinden,
     * wird gewartet bis mindestens 30 Zeichen eingegeben wurden (API-Key meist 34 Zeichen).
     * Nach 200 ms ohne weitere Eingabe wird die Funktion checkApiKey mit dem übergebenen API-Key
     * ausgeführt.
     * @type {{callback: waitTo.callback, wait: number, highlight: boolean, allowSubmit: boolean, captureLength: number}}
     */
    var waitTo = {
        callback: function (value) {
            console.log("Value: " + value);
            checkApiKey(value);
        },
        wait: 250,
        highlight: true,
        allowSubmit: true,
        captureLength: 30
    }


    $("#key").typeWatch(waitTo);

    function checkApiKey(key) {
        $('#load').show();
        post_data = {
            'key': key
        };
        $.post(url, post_data, function (response) {
            console.log("Response: " + response.type);
            if (response.type == 'error') {
                $('#load').hide();
                $('#more').hide();
                $("#key").css('border-color', 'red');
            } else {
                $('#load').hide();
                $('#more').show();
                console.log(response.text);
                $('#submit_btn').enable();
                for (var i = 0; i < response.text.length; i++) {
                    console.log("List: " + response.text[i]);
                    var name = response.text[i]['name'];
                    var id = response.text[i]['id'];
                    $('#lists').append($('<option>', {
                        value: id,
                        text: name
                    }));
                }
            }
        }, 'json');
    }

    var database = WPURLS.siteurl + "/wp-content/plugins/green-newsletter/lib/database.php";

    $('#submit_btn').click(function(){
        var key = $('#key').val();
        var list = $('#lists option:selected').val();
        var email = $('#email').val();
        var emailName = $('#emailName').val();
        var subject = $('#subject').val();
        var text = $('#text').val();
        var address = $('#address').val();
        var listID = $('#listID').val();

        console.log("ListID: " + listID);

        if(text.indexOf("?link") == -1){
            $('#text').css('border-color', 'red');
        }

        post_data = {
            'key': key,
            'list': list,
            'email': email,
            'emailName': emailName,
            'subject': subject,
            'text': text,
            'address': address
        }
        $.post(database, post_data, function(response) {
            if (response.type == 'error') {
                console.log("false");
            } else {
                console.log("true");
            }
        }, 'json');
    });
})
