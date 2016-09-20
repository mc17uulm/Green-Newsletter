<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 15.09.2016
 * Time: 16:13
 */

/**
 * Nach der Aktvierung des Plugins werden durch die initNewsletter-Funktion Seiten
 * und Datenbanktabellen erstellt, welche durch das Plugin befüllt werden.
 */
function initNewsletter(){

    global $wpdb;

    /**
     * Namde und Charset der Tabelle werden definiert
     */
    $table = $wpdb->prefix . 'green_widgets';
    $charset_collate = $wpdb->get_charset_collate();

    /**
     * Die erstellte Tabelle 'green_widgets' speichert
     * den apiKey des CleverReach Accounts, sowie die ID der Empfänger
     * Liste. Außerdem werden Informationen gespeichert, welche beim Senden
     * der Bestätigungsemails benötigt werden.
     */
    $sql = "CREATE TABLE $table (
            `apiKey` VARCHAR(155) NOT NULL,
            `listID` INT NOT NULL,
            `listName` VARCHAR(55) NOT NULL,
            `fromEmail` VARCHAR(55) NOT NULL,
            `fromName` VARCHAR(45) NOT NULL,
            `subject` VARCHAR(75) NOT NULL,
            `text` TEXT NOT NULL,
            `adress` TEXT NOT NULL,
            PRIMARY KEY (`apiKey`, `listID`)
            )$charset_collate;";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $table = $wpdb->prefix . 'green_keys';
    /**
     * Diese Tabelle speichert Keys, mit welchen sich die Nutzer
     * beim aktivieren/deaktivieren ihrer Accounts identifizeren können.
     */
    $sql = "CREATE TABLE $table (
            `ID` INT NOT NULL,
            `key` VARCHAR(255) NOT NULL,
            `userID` INT NOT NULL,
            PRIMARY KEY (`ID`)
            )$charset_collate;";

    dbDelta($sql);

    /**
     * Initial bei der Aktivierung des Plugins werden die
     * Seiten 'Subscribe' und 'Unsubscribe' erstellt, welche
     * die Tags [subscribe] und [unsubscribe] enthalten. Dadurch können
     * diese Seiten später angezeigt werden.
     */
    $subscribe = array(
        'post_content' => '[subscribe]',
        'post_title' => 'Subscribe',
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_name' => 'Subscribe',
        'post_content_filtred' => "[subscribe]",
        'post_type' => 'page'
    );

    $unsubscribe = array(
        'post_content' => '[unsubscribe]',
        'post_title' => 'Unsubscribe',
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_name' => 'Unsubscribe',
        'post_content_filtred' => "[unsubscribe]",
        'post_type' => 'page'
    );

    wp_insert_post($subscribe, $wp_error = false);
    wp_insert_post($unsubscribe, $wp_error = false);


}

function deleteNewsletter(){

    $pages = get_pages( array('post_title' => 'Subscribe', 'post_content' => '[subscribe]') );
    $pages .= get_pages( array('post_title' => 'Unsubscribe', 'post_content' => '[unsubscribe]') );

    foreach ( $pages as $page ) {

        wp_delete_post( $page->ID, true);
        echo $page;

    }

    global $wpdb;

    /**
     * Namde und Charset der Tabelle werden definiert
     */
    $table = $wpdb->prefix . 'green_widgets';
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql = "DROP TABLE IF EXISTS $table";
    $wpdb->query($sql);

    $table = $wpdb->prefix . 'green_keys';
    $sql = "DROP TABLE IF EXISTS $table";
    $wpdb->query($sql);


}