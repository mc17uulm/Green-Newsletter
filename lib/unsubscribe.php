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
        die($output);
    }

    $key = $_POST["key"];
    $id = get_userID($key);

    $widget = get_actual_widget();
    $wsdl = $widget -> getURL();
    $apiKey = $widget -> getApiKey();
    $listId = $widget -> getListID();

    $api = new SoapClient($wsdl);

    try{
        $result = $api->receiverGetById($apiKey, $listId, $id, 1);
    } catch(Exception $e){
        $output = json_encode(array('type' => 'message', 'text' => $e->getMessage()));
        die($output);
    }

    if($result->status=="SUCCESS"){
        $email = $result->data->email;
        try{
            $result = $api->receiverSetInactive($apiKey, $listId, $email);
        } catch (Exception $e){
            $output = json_encode(array('type' => 'error', 'text' => $e->getMessage()));
            die($output);
        }
        if($result->status=="SUCCESS"){
            $output = json_encode(array('type' => 'message', 'text' => 'success'));
            $name = $result->data->globalAtributes;
            sendUnsubscription($email, $name[0]->value, $name[1]->value);
            die($output);
        } else{
            $output = json_encode(array('type' => 'message', 'text' => 'error_delete'));
            die($output);
        }
    }else{
        $output = json_encode(array('type' => 'message', 'text' => $id));
        die($output);
    }
}

?>