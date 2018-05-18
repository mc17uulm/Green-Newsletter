<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
    die(json_encode(array(
        'type' => 'error',
        'text' => 'Request must be Ajax POST'
    )));
}

if((!isset($_POST["email"])) || (!isset($_POST["mode"]))){
    die(json_encode(array(
        'type' => 'error',
        'text' => 'Invalid request 1'
    )));
}

$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$mode = strip_tags(trim($_POST["mode"]));

if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    die(json_encode(array(
        'type' => 'error',
        'text' => 'Invalid request 2'
    )));
}

switch($mode){

    case "full":

        if((!isset($_POST["surname"])) || (!isset($_POST["lastname"]))){
            die(json_encode(array(
                'type' => 'error',
                'text' => 'Invalid request 3'
            )));
        }

        $surname = strip_tags(trim($_POST["surname"]));
        $lastname = strip_tags(trim($_POST["lastname"]));

        //TODO: implement full function

        die(json_encode(array(
            "type" => "success",
            "email" => $email,
            "surname" => $surname,
            "lastname" => $lastname
        )));
        break;

    case "standard":

        //TODO: implement standard function

        die(json_encode(array(
            "type" => "success",
            "email" => $email
        )));
        break;

}




?>