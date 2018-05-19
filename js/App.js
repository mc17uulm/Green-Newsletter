jQuery(document).ready(($) => {

    const btn = $('#green_newsletter_submit_btn');
    const info = $('#green_newsletter_info');

    let sn = $('#green_newsletter_surname');
    let ln = $('#green_newsletter_lastname');
    let em = $('#green_newsletter_email');
    let md = $('#green_newsletter_type');

    btn.on('click', (e) => {

        e.preventDefault();

        let proceed = true;
        let data = {};

        if((sn.length) && (ln.length)){

            if(!$.trim(sn.val())){
                sn.css('border-color', 'red');
                proceed = false;
            } else{
                sn.css('border-color', 'green');
                data["surname"] = sn.val();
            }

            if(!$.trim(ln.val())){
                ln.css('border-color', 'red');
                proceed = false;
            } else{
                ln.css('border-color', 'green');
                data["lastname"] = ln.val();
            }

        }

        const email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if((!$.trim(em.val())) || (!email_reg.test($.trim(em.val())))){
            em.css('border-color', 'red');
            proceed = false;
        } else{
            em.css('border-color', 'green');
            data["email"] = em.val();
        }

        if(proceed){

            data["mode"] = md.val();

            // btn.prop("disabled", true);

            $.ajax({

                type: "POST",
                url: WPURLS.siteurl + "/wp-content/plugins/Green-Newsletter/classes/Request.php",
                data: data,
                dataType: 'json',
                success: (d) => {
                    if(d["type"] === "error"){
                        info.html(`<h3>Fehler!</h3><p>${d["text"]}</p>`);
                    } else{
                        info.html(`<h3>Erfolg!</h3><p>${d["text"]}</p>`);
                    }
                },
                error: (e) => {

                }

            });

        }


    });

    $("#green_newsletter_form input[required=true]").keyup(() => {
        $(this).css('border-color', '');
    });

});