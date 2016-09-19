<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 14.08.2016
 * Time: 13:11
 */
include_once 'functions.php';
include_once 'api.php';
include_once 'log.php';
include_once 'sendReport.php';

if($_POST){
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
        $output = json_encode(array(
            'type' => 'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        logger("add.php:12", "ERROR", "REQUEST IS NO AJAX POST");
        die($output);
    }

    $prename = filter_var($_POST["prename"], FILTER_SANITIZE_EMAIL);
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_EMAIL);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $output = json_encode(array('type'=>'error', 'text'=> 'Bitte geben Sie eine gültige E-Mail-Adresse ein!'));
        logger("add.php:25", "ERROR", "SUMBITED ADDRESS IS NO EMAIL ADDRESS");
        die($output);
    } else{
        $code = new API();
        $wsdl = $code -> getSoapUrl();
        $apiKey = $code -> getApiKey();
        $listId = $code -> getListID();

        $api = new SoapClient($wsdl);
        
        $user = array(
            "email" => $email,
            "registered" => time(),
            "source" => "Anmelde Plugin",
            "active" => "false",
            "attributes" => array(
                0 => array("key" => "firstname", "value" => $prename),
                1 => array("key" => "lastname", "value" => $lastname),
            ),
        );

        try{
            $result = $api->receiverAdd($apiKey, $listId, $user);
        } catch(Exception $e){
            $output = json_encode(array('type' => 'error', 'text' => 'Fehler', 'Exception' => $e->getMessage()));
            logger("add.php:49", "EXCEPTIONS", $e->getMessage());
            die($output);
        }

        if($result->status=="SUCCESS"){
            error_reporting(E_ALL);

            $id = $result->data->id;
            $key = getKey($id);

            require_once 'phpmailer/class.phpmailer.php';

            $mail = new PHPMailer();
            $mail -> isHTML(true);
            $mail -> CharSet = 'utf-8';
            $mail -> SetLanguage ("de");
            $text = "<html><body><h3>Anmeldung Newsletter | Gr&uuml;ne Heidenheim</h3><br />";
            $text .= "<p>Vielen Dank f&uuml;r Ihr Interesse an unserem Newlsetter. Um Ihre Registrierung abzuschlie&szlig;en,";
            $text .= "<br/>klicken Sie bitte auf folgenden Link: <a href='http://gruene-heidenheim.de/subscribe?key=" . $key . "'>Best&auml;tigen</a><br />";
            $text .= "<br />Sollten Sie sich nicht auf gruene-heidenheim.de f&uuml;r den Newsletter angemeldet haben, k&ouml;nnen Sie diese E-Mail ignorieren!<br />";
            $text .= "<br />-----------------<br /><small>B&uuml;ndnis 90/Die Gr&uuml;nen Heidenheim<br />Talhofstra&szlig;e 30<br />89518 Heidenheim<br />E-Mail: <a href='mailto:info@gruene-heidenheim.de'>info@gruene-heidenheim.de</a><br />Tel.: 07321 - 730 20 46</small></p></body></html>";

            $mail -> From = "info@gruene-heidenheim.de";
            $mail -> FromName = "Grüne Heidenheim";
            $mail -> AddAddress($email);
            $mail -> Subject = "Anmeldung | Newsletter Grüne Heidenheim";

            $mail -> Body = nl2br($text);

            $mail -> AltBody = strip_tags($text);
            if($mail -> send()){
                    $output = json_encode(array('type' => 'message', 'text' => $key));
                    logger("add.php:82", "INFO", "SUBSCRIPTION MAIL SEND TO: $email");
                    sendSubscription($email, $prename, $lastname);
                    die($output);
            } else{
                    $output = json_encode(array('type' => 'error', 'text' => $mail -> ErrorInfo));
                    logger("add.php:82", "ERROR", "MAIL NOT SEND: " . $mail -> ErrorInfo);
                    die($output);
            }
        }else{
            $message = $result->message;
            if($message=="duplicate data"){
                $output = json_encode(array('type' => 'error', 'text' => '<strong>Sie sind bereits angemeldet!</strong>'));
                logger("add.php:56", "ERROR", "RESULT STATUS: ERROR | MESSAGE: $message");
            } else{
                $output = json_encode(array('type' => 'error', 'text' => 'Unbekannter Fehler'));
                logger("add.php:56", "ERROR", "RESULT STATUS: ERROR | MESSAGE: $message");
            }
            die($output);
        }
    }
}

?>