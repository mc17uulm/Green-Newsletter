<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15.08.2016
 * Time: 14:32
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
        logger("unsubscribe.php:13", "ERROR", "REQUEST IS NO AJAX POST");
        die($output);
    }

    $key = $_POST["key"];
    $id = getID($key);

    logger("unsubscribe.php", "INFO", "ID: $id");

    $code = new API();
    $wsdl = $code -> getSoapUrl();
    $apiKey = $code -> getApiKey();
    $listId = $code -> getListID();

    $api = new SoapClient($wsdl);

    try{
        $result = $api->receiverGetById($apiKey, $listId, $id, 1);
    } catch(Exception $e){
        $output = json_encode(array('type' => 'message', 'text' => $e->getMessage()));
        logger("unsubscribe.php:35", "EXCEPTION", $e->getMessage());
        die($output);
    }

    if($result->status=="SUCCESS"){
        $email = $result->data->email;
        try{
            $result = $api->receiverSetInactive($apiKey, $listId, $email);
        } catch (Exception $e){
            $output = json_encode(array('type' => 'error', 'text' => $e->getMessage()));
            logger("unsubscribe.php:45", "EXCEPTION", $e->getMessage());
            die($output);
        }
        if($result->status=="SUCCESS"){
            $output = json_encode(array('type' => 'message', 'text' => 'success'));
            logger("unsubscribe.php", "INFO", "UNSUBSCRIBE $email");
            $name = $result->data->globalAtributes;
            sendUnsubscription($email, $name[0]->value, $name[1]->value);
            die($output);
        } else{
            $output = json_encode(array('type' => 'message', 'text' => 'error_delete'));
            logger("unsubscribe.php:51", "ERROR", "RESULT STATUS: ERROR");
            die($output);
        }
    }else{
        $output = json_encode(array('type' => 'message', 'text' => $id));
        logger("unsubscribe.php:42", "ERROR", "RESULT STATUS: ERROR");
        die($output);
    }
}

?>