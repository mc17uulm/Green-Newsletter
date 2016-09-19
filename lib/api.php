<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 16.08.2016
 * Time: 19:04
 */

class API{

    private $apiKey = "1bad07821f9619b2faa9bcb24ade6c66-2";
    private $wsdl_url= "http://api.cleverreach.com/soap/interface_v5.1.php?wsdl";
    private $listId = "505937";

    function getApiKey(){
        return $this -> apiKey;
    }

    function getSoapUrl(){
        return $this -> wsdl_url;
    }

    function getListID(){
        return $this -> listId;
    }

    function setApiKey($key){
        $this -> apiKey = $key;
    }

    function setSoapUrl($url){
        $this -> wsdl_url = $url;
    }

    function  setListID($id){
        $this -> listId = $id;
    }

}
?>