<?php
    if($header){
        ?>
    <h2><?= $title ?></h2>
        <?php
    }
?>
    <form id="green_newsletter_form" class="style-1">
        <?php
            if($full){
        ?>
        <fieldset class="form-group">
            <label>Vorname:</label>
            <input type="text" id="green_newsletter_surname" class="form-control" required="true" placeholder="Vorname" value="Marco">
        </fieldset>
        <fieldset class="form-group">
            <label>Nachname:</label>
            <input type="text" id="green_newsletter_lastname" class="form-control" required="true" placeholder="Nachname" value="Combosch">
        </fieldset>
        <?php
            }
        ?>
        <fieldset class="form-group">
            <label>E-Mail-Adresse:</label>
            <input type="email" id="green_newsletter_email" class="form-control" required="true" placeholder="E-Mail-Adresse" value="m.combosch@web.de">
        </fieldset>
        <?php
            if($captcha){
        ?>
        <fieldset class="form-group">
            <label>Captcha:</label>
            <!-- TODO -->
        </fieldset>
        <?php
            }
        ?>
        <input type="hidden" id="green_newsletter_type" value="<?= $mode ?>">
        <button type="button" id="green_newsletter_submit_btn" class="green_newsletter_btn"><i class="fa fa-paper-plane" aria-hidden="true"></i> Senden</button>
    </form>
    <br>
    <div id="green_newsletter_info"></div>