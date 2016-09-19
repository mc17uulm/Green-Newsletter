<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 16.09.2016
 * Time: 21:03
 */

if($_POST) {

    $wsdl_url= "http://api.cleverreach.com/soap/interface_v5.1.php?wsdl";

    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        $output = json_encode(array(
            'type' => 'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        logger("unsubscribe.php:13", "ERROR", "REQUEST IS NO AJAX POST");
        die($output);
    }

    $key = $_POST["key"];

    $api = new SoapClient($wsdl_url);

    try{
        $result = $api->clientGetDetails($key);
    } catch (Exception $e){
        $output = json_encode(array('type' => 'error', 'text' => $e->getMessage()));
        die($output);
    }

    if($result->status == "SUCCESS"){
        try{
            $result = $api->groupGetList($key);
        } catch (Exception $e){
            $output = json_encode(array('type' => 'error', 'text' => $e->getMessage()));
            die($output);
        }
        if($result->status = "SUCCESS"){
            $data = $result->data;
            $output = json_encode(array('type' => 'success', 'text' => $data));
            die($output);
        } else{
            $output = json_encode(array('type' => 'error', 'text' => 'No Lists'));
            die($output);
        }
    } else{
        $output = json_encode(array('type' => 'error', 'text' => 'No API-Key'));
        die($output);
    }


}