<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15.08.2016
 * Time: 16:32
 */

include_once 'functions.php';
include_once 'api.php';
include_once 'log.php';

function subscribe($key){

    $id = getID($key);

    logger("subscribe.php", "INFO", "ID: $id");

    $code = new API();
    $wsdl = $code -> getSoapUrl();
    $apiKey = $code -> getApiKey();
    $listId = $code -> getListID();

    $api = new SoapClient($wsdl);

    try{
        $result = $api->receiverGetById($apiKey, $listId, $id, 1);
    } catch(Exception $e){
        $out = "ID: " . $id . " EXEP: " . $e->getMessage();
        logger("subscribe.php:25", "EXCEPTION", $e->getMessage());
        return $out;
    }

    if($result->status=="SUCCESS"){
        $email = $result->data->email;
        try{
            $result = $api->receiverSetActive($apiKey, $listId, $email);
        } catch (Exception $e){
            $out = "EMAIL: " . $email . " EXEP: " . $e->getMessage();
            logger("subscribe.php:35", "EXCEPTION", $e->getMessage());
            return $out;
        }
        if($result->status=="SUCCESS"){
           return "success";
        } else{
            logger("subscribe.php:41", "ERROR", "RESULT STATUS: ERROR");
            return "error";
        }
    }else{
        logger("subscribe.php:32", "ERROR", "RESULT STATUS: ERROR");
        return $result->message;
    }
}