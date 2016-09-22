/**
 * Created by Marco on 16.09.2016.
 */

jQuery(document).ready(function($) {

    function validateEmail(email){
        var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@uni-ulm.de$/;
        return regex.test(email);
    }

    // URL zur PHP File durch welche der API-Key auf Gültigkeit überprüft wird
    var url = WPURLS.siteurl + "/wp-content/plugins/green-newsletter/lib/checkApi.php";

    $('#load').hide();

    if($('#key').val() == ''){
        $("#key").css('border-color', 'none');
    } else{
        checkApiKey($('#key').val());
    }

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
    };


    $("#key").typeWatch(waitTo);

    function checkApiKey(key) {
        // Load gif: https://commons.wikimedia.org/wiki/File:Android_style_loader.gif
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
                $('#submit_btn').prop('disabled', true);
            } else {
                $('#load').hide();
                $('#more').show();
                console.log(response.text);
                $('#submit_btn').prop('disabled', false);
                $('#key').css('border-color', 'green');
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
        var list = $('#lists option:selected').text();
        var listID = $('#lists option:selected').val();
        console.log("LIST: " + list + " LIST ID: " + listID);
        if((list == null) || (list == "") || (list == null) || (list == "")){
            $("#lists").css('border-color', 'red');
            return false;
        } else{
            $("#lists").css('border-color', 'green');
        }
        var email = $('#email').val();
        if((email == null) || (email == "") || (validateEmail(email))){
            $("#email").css('border-color', 'red');
            return false;
        } else{
            $("#email").css('border-color', 'green');
        }
        var emailName = $('#emailName').val();
        if((emailName == null) || (emailName == "")){
            $("#emailName").css('border-color', 'red');
            return false;
        } else{
            $("#emailName").css('border-color', 'green');
        }
        var subject = $('#subject').val();
        if((subject == null) || (subject == "")){
            $("#subject").css('border-color', 'red');
            return false;
        } else{
            $("#subject").css('border-color', 'green');
        }
        console.log("Subject: " + subject);
        var text = $('#text').val();
        console.log("TEXT: " + text);
        if((text == null) || (text == "")){
            $("#text").css('border-color', 'red');
            return false;
        } else{
            $("#text").css('border-color', 'green');
        }
        var address = $('#address').val();
        if((address == null) || (address == "")){
            $("#address").css('border-color', 'red');
            return false;
        } else{
            $("#address").css('border-color', 'green');
        }

        console.log("ListID: " + listID);

        console.log("Index Of: " + text.toString().indexOf("?link"));

        if(text.toString().indexOf("?link") == -1){
            $('#text').css('border-color', 'red');
            return false;
        }

        var ID = $('#ID').val();
        var first = false;
        if((ID == null) || (ID == "")){
            first = true;
        }

        console.log("First: " + first);

        post_data = {
            'id': ID,
            'key': key,
            'list': list,
            'listID': listID,
            'email': email,
            'emailName': emailName,
            'subject': subject,
            'text': text,
            'address': address,
            'first': first
        };
        $.post(database, post_data, function(response) {
            if(response.type == 'success'){
                console.log(response.text);
            } else{
                console.log(response.text);
            }
        }, 'json');
    });
});
