<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 28.08.2016
 * Time: 10:51
 */

include_once 'phpmailer/class.phpmailer.php';

function sendSubscription($email, $prename, $lastname){

    $mail = new PHPMailer();
    $mail -> isHTML(true);
    $mail -> CharSet = 'utf-8';
    $mail -> SetLanguage ("de");
    $text = "<html><body><h3>Neue Anmeldung Newsletter | Gr&uuml;ne Heidenheim</h3><br />";
    $text .= "<p>Gerade hat sich ein neuer Nutzer auf gruene-heidenheim.de<br />";
    $text .= "f&uuml;r den Newsletter angemeldet: <br /><br />";
    $text .= "Vorname: $prename<br />";
    $text .= "Nachname: $lastname<br />";
    $text .= "Email: $email<br /></p>";
    $text .= "<br />-----------------<br /><small>B&uuml;ndnis 90/Die Gr&uuml;nen Heidenheim<br />Talhofstra&szlig;e 30<br />89518 Heidenheim<br />E-Mail: <a href='mailto:info@gruene-heidenheim.de'>info@gruene-heidenheim.de</a><br />Tel.: 07321 - 730 20 46</small></p></body></html>";

    $mail -> From = "webmaster@gruene-heidenheim.de";
    $mail -> FromName = "Grüne Heidenheim";
    $mail -> AddAddress("marco.combosch@uni-ulm.de");
    $mail -> Subject = "Neue Anmeldung | Newsletter Grüne Heidenheim";

    $mail -> Body = nl2br($text);

    $mail -> AltBody = strip_tags($text);
    if($mail -> send()){
        return "success";
    } else{
        return "error";
    }

}

function sendUnsubscription($email, $prename, $lastname){

    $mail = new PHPMailer();
    $mail -> isHTML(true);
    $mail -> CharSet = 'utf-8';
    $mail -> SetLanguage ("de");
    $text = "<html><body><h3>Abmeldung Newsletter | Gr&uuml;ne Heidenheim</h3><br />";
    $text .= "<p>Gerade hat sich ein neuer Nutzer auf gruene-heidenheim.de<br />";
    $text .= "vom Newsletter abgemeldet: <br /><br />";
    $text .= "Vorname: $prename<br />";
    $text .= "Nachname: $lastname<br />";
    $text .= "Email: $email<br /> <b>Der Nutzer muss noch manuell gelöscht werden</b><br /></p>";
    $text .= "<br />-----------------<br /><small>B&uuml;ndnis 90/Die Gr&uuml;nen Heidenheim<br />Talhofstra&szlig;e 30<br />89518 Heidenheim<br />E-Mail: <a href='mailto:info@gruene-heidenheim.de'>info@gruene-heidenheim.de</a><br />Tel.: 07321 - 730 20 46</small></p></body></html>";

    $mail -> From = "webmaster@gruene-heidenheim.de";
    $mail -> FromName = "Grüne Heidenheim";
    $mail -> AddAddress("marco.combosch@uni-ulm.de");
    $mail -> Subject = "Abmeldung | Newsletter Grüne Heidenheim";

    $mail -> Body = nl2br($text);

    $mail -> AltBody = strip_tags($text);
    if($mail -> send()){
        return "success";
    } else{
        return "error";
    }

}

