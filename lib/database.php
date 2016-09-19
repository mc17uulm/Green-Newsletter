<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 18.09.2016
 * Time: 14:00
 */

include_once 'widget.php';

if($_POST) {

    $wsdl_url = "http://api.cleverreach.com/soap/interface_v5.1.php?wsdl";

    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        $output = json_encode(array(
            'type' => 'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        logger("unsubscribe.php:13", "ERROR", "REQUEST IS NO AJAX POST");
        die($output);
    }

    global $wpdb;

    $table = $wpdb->prefix . 'green_widgets';

    $key = $_POST["key"];
    $list = $_POST["list"];
    $email = $_POST["email"];
    $emailName = $_POST["emailName"];
    $subject = $_POST["subject"];
    $text = $_POST["text"];
    $address = $_POST["address"];

    $wpdb->insert( $wpdb->$table, array(
        "apiKey" => $key,
        "listID" => $list,
        "fromEmail" => $email,
        "fromName" => $emailName,
        "subject" => $subject,
        "text" => $text,
        "address" => $address
    ));

}

function get_actual_widget(){

    global  $wpdb;

    $table = $wpdb->prefix . 'green_widgets';

    $sql = "SELECT * FROM $table";

    $result = $wpdb->get_result($sql) or die(mysql_error);

    $out = array();

    foreach( $result as $widget){
        array_push($out, new widget($widget->apiKey, $widget->listID, $widget->listName, $widget->fromEmail, $widget->fromName, $widget->subject, $widget->text, $widget->address));
    }

    return $out[0];
}