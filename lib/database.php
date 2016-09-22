<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 18.09.2016
 * Time: 14:00
 */

include_once 'widget.php';

if($_POST) {

    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );

    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        $output = json_encode(array(
            'type' => 'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output);
    }

    $id = $_POST["id"];
    $key = $_POST["key"];
    $list = $_POST["list"];
    $listID = $_POST["listID"];
    $email = $_POST["email"];
    $emailName = $_POST["emailName"];
    $subject = $_POST["subject"];
    $text = $_POST["text"];
    $address = $_POST["address"];
    $first = $_POST["first"];

    global $wpdb;

    $table = $wpdb->prefix . 'green_widgets';

    if($first === "true"){
        try {
            $wpdb->insert($table, array(
                "apiKey" => $key,
                "listID" => $listID,
                "listName" => $list,
                "fromEmail" => $email,
                "fromName" => $emailName,
                "subject" => $subject,
                "text" => $text,
                "address" => $address
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
    } else{
        try {
            $updated = $wpdb->update($table, array(
                "apiKey" => $key,
                "listID" => $listID,
                "listName" => $list,
                "fromEmail" => $email,
                "fromName" => $emailName,
                "subject" => $subject,
                "text" => $text,
                "address" => $address
            ), array( 'ID' => $id ));
            if ( false === $updated ) {
                $output = json_encode(array(
                    'type' => 'error',
                    'text' => 'no update'
                ));
                die($output);
            } else {
                $output = json_encode(array(
                    'type' => 'success',
                    'text' => 'update Database'
                ));
                die($output);
            }
        } catch (Exception $e){
            $output = json_encode(array(
                'type' => 'error',
                'text' => 'Exception: ' . $e->getMessage()
            ));
            die($output);
        }
    }
}