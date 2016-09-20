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
    $listID = $_POST["listID"];
    $email = $_POST["email"];
    $emailName = $_POST["emailName"];
    $subject = $_POST["subject"];
    $text = $_POST["text"];
    $address = $_POST["address"];
    $first = $_POST["first"];

    // TODO: Error: [client 127.0.0.1:60900] [host ...]
    // TODO: PHP Fatal error:  Call to a member function insert() on null in .../wp-content/plugins/green-newsletter/lib/database.php on line 50
    if(!$first){
        $wpdb->update( $wpdb->$table, array(
            "apiKey" => $key,
            "listID" => $listID,
            "listName" => $list,
            "fromEmail" => $email,
            "fromName" => $emailName,
            "subject" => $subject,
            "text" => $text,
            "address" => $address
        ), "WHERE listID = $listID");
    } else{
        $wpdb->insert( $wpdb->$table, array(
            "apiKey" => $key,
            "listID" => $listID,
            "listName" => $list,
            "fromEmail" => $email,
            "fromName" => $emailName,
            "subject" => $subject,
            "text" => $text,
            "address" => $address
        ), null);
    }
}