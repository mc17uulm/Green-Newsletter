<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 17.08.2016
 * Time: 17:15
 */

include_once 'functions.php';
// include_once 'api.php';

function get($key){

    $id = getID($key);

    $code = new API();
    $wsdl = $code -> getSoapUrl();
    $apiKey = $code -> getApiKey();
    $listId = $code -> getListID();

    $api = new SoapClient($wsdl);

    try{
        $result = $api->receiverGetById($apiKey, $listId, $id, 1);
    } catch(Exception $e){
        $out = "ID: " . $id . " EXEP: " . $e->getMessage();
        return $out;
    }

    if($result -> status ="SUCCESS"){
        $data = $result -> data;
        $attr = $data -> globalAttributes;
        $prename = $attr[0] -> value;
        $lastname =$attr[1] -> value;
        $email = $data -> email;
        $active = getBool($data -> active);
        return "<div class='info' id='info'><p>" .
                "<form id=\"newsletter_info\">" .
                "<fieldset class=\"form-group\">".
                "<label>Vorname:</label>" .
                "<input type=\"text\" id=\"prename\" name=\"prename\" class=\"form-control\" required=\"true\" value=" . $prename . ">" .
                "</fieldset>" .
                "<fieldset class=\"form-group\">".
                "<label>Nachname:</label>" .
                "<input type=\"text\" id=\"lastname\" name=\"lastname\" class=\"form-control\" required=\"true\" value=" . $lastname . ">" .
                "</fieldset>" .
                "<fieldset class=\"form-group\">".
                "<label>Email:</label>" .
                "<input type=\"email\" id=\"email\" name=\"email\" class=\"form-control\" required=\"true\" value=" . $email . ">" .
                "</fieldset>" .
                "<fieldset class=\"form-group\">".
                "<label>Active:</label>" .
                "<div class=\"checkbox\"><label><input type=\"checkbox\" checked=" . $active . ">Abo aktiviert</label></div>" .
                "</fieldset>" .
                "<button type=\"button\" id=\"submit_info\" class=\"btn btn-success\">Abschicken</button>" .
                "</form>" .
                "</p></div>";
    } else{
        return "error";
    }



}