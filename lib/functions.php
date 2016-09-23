<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15.08.2016
 * Time: 19:16
 */

include_once 'widget.php';

$parse_uri = $_SERVER["DOCUMENT_ROOT"] . "/" . explode( 'wp-content',str_replace($_SERVER["DOCUMENT_ROOT"],'', str_replace('\\','/',__FILE__ ) ) )[0];
require_once( $parse_uri . 'wp-load.php' );


function makeKey(){
    return md5(uniqid(rand(), true));
}

function getBool($val){
    if($val == "1"){
        return "checked";
    } else{
        return "";
    }
}

function get_HTML_Mail_Text($widget, $key){
    $rawText = $widget -> getText();
    $indexArr = strpos_r($rawText, "?link");
    $link = "" . get_option('siteurl') . "/subscribe?key=$key";

    $htmlText = "<html><body><h3>" . $widget -> getSubject() . "</h3><br />";
    $htmlText .= "<p>" . getLinkInText($rawText, $indexArr, $link) . "</p><br />";
    $htmlText .= "-------------------<br /><small>" . $widget -> getAddress() . "</small></body></html>";

    return $htmlText;

}

function strpos_r($haystack, $needle)
{
    if(strlen($needle) > strlen($haystack)) {
        return array();
    }

    $seeks = array();
    while($seek = strripos($haystack, $needle))
    {
        array_push($seeks, $seek);
        $haystack = substr($haystack, 0, $seek);
    }
    return $seeks;
}

function getLinkInText($text, $arr, $link){

    $link = "<a href='" . $link . "'>Bestätigen</a>";

    $size = sizeof($arr);
    $out = $text;

    for($i = 0; $i < $size; $i++){
        $out = substr($out, 0, $arr[$i]) . $link . substr($out, $arr[$i] + 5, strlen($out));
    }

    return $out;
}

function get_actual_widget(){

    global  $wpdb;

    $table = $wpdb->prefix . 'green_widgets';

    $sql = "SELECT * FROM $table";

    $result = $wpdb->get_results($sql, ARRAY_A);

    $widget = $result[0];
    $out = new widget($widget['ID'], $widget['apiKey'], $widget['listID'], $widget['listName'], $widget['fromEmail'], $widget['fromName'], $widget['subject'], $widget['text'], $widget['address']);

    return $out;
}

function create_new_key($key, $userID){
    global $wpdb;

    $table = $wpdb->prefix . 'green_keys';
    try {
        $wpdb->insert($table, array(
            "key" => $key,
            "userID" => $userID
        ));
        $output = json_encode(array(
            'type' => 'success',
            'text' => 'insert Database'
        ));
        die($output);
    } catch(Exception $e){
        $output = json_encode(array(
            'type' => 'error',
            'text' => 'Exception: ' . $e->getMessage()
        ));
        die($output);
    }
}

function get_userID($key){
    global  $wpdb;

    $table = $wpdb->prefix . 'green_keys';

    $sql = "SELECT * FROM $table WHERE key = $key";

    $result = $wpdb->get_results($sql, ARRAY_A);

    $key = $result[0];
    $out = new key($key['ID'], $key['key'], $key['userID']);

    return $out;
}