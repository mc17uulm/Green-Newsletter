<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15.08.2016
 * Time: 16:32
 */

include_once 'functions.php';
// include_once 'api.php';
include_once 'log.php';

function subscribe($key){

    $id = get_userID($key);
    $widget = get_actual_widget();

    $wsdl = $widget -> getURL();
    $apiKey = $widget -> getApiKey();
    $listId = $widget -> getListID();

    $api = new SoapClient($wsdl);

    try{
        $result = $api->receiverGetById($apiKey, $listId, $id, 1);
    } catch(Exception $e){
        $out = "ID: " . $id . " EXEP: " . $e->getMessage();
        return $out;
    }

    if($result->status=="SUCCESS"){
        $email = $result->data->email;
        try{
            $result = $api->receiverSetActive($apiKey, $listId, $email);
        } catch (Exception $e){
            $out = "EMAIL: " . $email . " EXEP: " . $e->getMessage();
            return $out;
        }
        if($result->status=="SUCCESS"){
           return "success";
        } else{
            return "error";
        }
    }else{
        return $result->message;
    }
}