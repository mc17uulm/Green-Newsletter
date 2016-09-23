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
        die($output);
    }

    $prename = filter_var($_POST["prename"], FILTER_SANITIZE_EMAIL);
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_EMAIL);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $output = json_encode(array('type'=>'error', 'text'=> 'Bitte geben Sie eine gültige E-Mail-Adresse ein!'));
        die($output);
    } else{
        $widget = get_actual_widget();
        $wsdl = $widget -> getURL();
        $apiKey = $widget -> getApiKey();
        $listId = $widget -> getListID();

        $api = new SoapClient($wsdl);
        
        $user = array(
            "email" => $email,
            "registered" => time(),
            "source" => "Green Newsletter WP Anmelde Plugin",
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
            die($output);
        }

        if($result->status=="SUCCESS"){
            error_reporting(E_ALL);

            $id = $result->data->id;
            $key = makeKey();

            require_once 'phpmailer/class.phpmailer.php';

            $mail = new PHPMailer();
            $mail -> isHTML(true);
            $mail -> CharSet = 'utf-8';
            $mail -> SetLanguage ("de");
            $text = get_HTML_Mail_Text($widget, $key);

            $mail -> From = $widget -> getFromEmail();
            $mail -> FromName = $widget -> getFromName();
            $mail -> AddAddress($email);
            $mail -> Subject = $widget -> getSubject();

            $mail -> Body = nl2br($text);

            $mail -> AltBody = strip_tags($text);
            if($mail -> send()){
                    $output = json_encode(array('type' => 'message', 'text' => $key));
                    sendSubscription($email, $prename, $lastname);
                    create_new_key($key, $id);
                    die($output);
            } else{
                    $output = json_encode(array('type' => 'error', 'text' => $mail -> ErrorInfo));
                    die($output);
            }
        }else{
            $message = $result->message;
            if($message=="duplicate data"){
                $output = json_encode(array('type' => 'error', 'text' => '<strong>Sie sind bereits angemeldet!</strong>'));
            } else{
                $output = json_encode(array('type' => 'error', 'text' => 'Unbekannter Fehler'));
            }
            die($output);
        }
    }
}

?>