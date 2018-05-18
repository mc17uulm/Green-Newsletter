<?php
/*
Plugin Name: Green Newsletter Widget
Description: This plugin creates a input form to add new subscribers to the newsletter service
Author: mc17uulm
Author URI: https://combosch.de
Version: 2.0
License: GPLv3

=== License Information ===

Newsletter Widget is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

Newsletter Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Newsletter Widget. If not, see http://www.gnu.org/licenses/gpl-3.0.html.

=== Plugin Information ===

Version: 2.0
Date: 18.05.2018
Sollte es Probleme, Fehler oder Anmerkungen zu diesem Plugin geben, so kontaktieren Sie mich bitte unter: marco[dot]combosch[at]uni-ulm[dot]de

 */

/**
 * Hauptklasse des Plugins. Hier werden die Shortcodes verarbeitet:
 *
 * [newsletter]
 * [unsubscribe]
 * [subscribe]
 */

// Einbinden der benötigten Klassen
// include_once 'lib/subscribe.php';
// include_once 'lib/get.php';
// include_once 'lib/init.php';
// include_once 'lib/widget.php';

require_once 'classes/Shortcode.class.php';

// Name des Servers
$server = $_SERVER['SERVER_NAME'];

// register_activation_hook(__FILE__, 'green_newsletter_create_db');
// register_deactivation_hook(__FILE__, 'green_newsletter_uninstall');

// function green_newsletter_create_db(){

//     initNewsletter();

// }

// function green_newsletter_uninstall(){
//     deleteNewsletter();
// }

$local = '/wp-content/plugins/green-newsletter/';

function green_newsletter_enqueue_style(){

    global $local;

    wp_enqueue_style('Newsletter Style', $local . 'css/style.css', array(), false, 'all');
}

function green_newsletter_enqueue_script(){

    global $local;

    wp_enqueue_script('Newsletter', $local . 'js/App.js', array('jquery'), null, false);
    wp_localize_script('Newsletter', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));

}

// function green_newsletter_enqueue_admin_script(){

//     global $local;
//     wp_enqueue_script('niceEdit', 'http://js.nicedit.com/nicEdit-latest.js', array('jquery'), null, false);
//     wp_enqueue_script('Typewatch', $local . 'js/jquery.typewatch.js', array('jquery'), null, false);
//     wp_enqueue_script('adminNewsletter', $local . 'js/edit_info.js', array('jquery'), null, false);
//     wp_enqueue_style('Newsletter Style', $local . 'css/style.css', array(), false, 'all');
//     wp_localize_script('adminNewsletter', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));


// }

add_action( 'wp_enqueue_scripts', 'green_newsletter_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'green_newsletter_enqueue_script' );
// add_action( 'admin_enqueue_scripts', 'green_newsletter_enqueue_admin_script' );

/**
 * @param $atts
 * @return string
 * Diese Funktion erstellt den HTML Code für den [newsletter] Shortcode
 */
function newsletter_shortcode($atts){

   Shortcode::render("standard", "Newsletter");
    
}

// Erstellt für den [unsubscribe]-Shortcode den HTML Code
// Hier werden zwei Buttons erstellt, welche
function newsletter_unsubscribe($atts){
    global $server;
    if(isset($_GET['key'])){

        // Der Key ist eine durch ein Verschlüsselungsalgorithmus erstellte Zahlenfolge, welche die User ID verschlüsselt.
        $key = $_GET['key'];

        return "<p>Möchten Sie sich wirklich auf dem Newsletter abmelden?<br />" .
                "<br /> <button type=\"button\" id=\"unsubscribe\" class=\"errorBtn\" onclick=\"unsubscribe(" . $key . ");\">Abmelden</button>   " .
                "<button type=\"button\" id=\"back\" class=\"successBtn\" onclick=\"history.go(-1); return false;\">Zurück</button><br />" .
                "<div hidden class=\"errorNL\" id=\"errorNL\"></div>".
                "<div hidden class=\"successNL\" id=\"successNL\"></div>";
    } else{

        // Sollte kein Attribut gesetzt worden sein, wird der Nutzer auf die Main-Site umgeleitet.
        header('Location: ' . $server);
        die();
    }
}

function newsletter_subscribe($atts){

    global $server;
    global $local;

    if(isset($_GET['key'])){
        $key = $_GET['key'];

        // Durch das Aufrufen der Funktion 'subscribe', wird der Benutzer aktiviert (Double In-Out)
        $out = subscribe($key);
        if($out == "success"){
            return "<div class=\"successNL\" id=\"successNL\"><p>Sie haben Ihre Registrierung abgeschlossen<br />".
                    "Weitere Optionen: <a href='" . get_option('siteurl') .  $local . "/unsubscribe?key=" . $key . "'>Abmelden</a>" . "</p></div>";
        } else{
            return "<div class=\"errorNL\" id=\"errorNL\"><p>Leider gab es ein Problem. Versuchen Sie es bitte noch einmal,<br />" .
                    "oder kontaktieren Sie den Administrator! " . $out . "</p></div>";
        }
    } else{
        header('Location: ' . $server);
    }
}

// Die Funktion für den [edit_newsletter] Shortcode ist zur Zeit leider noch nicht aktivitert
function edit_newsletter($atts){
    /**global $server;
    if(isset($_GET['key'])){
        $key = $_GET['key'];
        $data = get($key);
        if($data == "error"){
            return "<div class=\"errorNL\" id=\"errorNL\"><p>Leider gab es ein Problem. Versuchen Sie es bitte noch einmal,<br />" .
                    "oder kontaktieren Sie den Administrator!</p></div>";
        } else{
            return $data;
        }
    } else{
        header('Loaction: ' . $server);
        die();
    }*/
    return "";
}

function  green_newsletter_option_page(){

    $widget = get_actual_widget();

    ?>
    <div class="wrap">
        <h1>Connect mit CleverReach:</h1>
        <p>Um sich mit deinem Cleverreach Account zu verbinden, benötigen wir<br />
            einen API-Key. Wie du diesen findest, kannst du hier nachlesen: <a href="#">Api-Key Cleverreach</a>.<br />
            Nach Eingabe des Schlüßels, kannst du die Liste auswählen, in welche die<br />
            neuen Abonnenten eingetragen werden sollen.
        </p>
        <div hidden class="errorNL" id="errorNL"></div>
        <div hidden class="successNL" id="successNL"></div>
        <form id="key_form" action="#">
            <table class="form-table">
                <input id="ID" type="text" hidden value="<?php echo $widget->getID(); ?>">
                <tr>
                    <th scope="row"><label for="key">Api-Key:</label></th>
                    <td><input type="text" id="key" name="key" class="regular-text" required="true" value="<?php echo $widget->getApiKey(); ?>" placeholder="Api-Key eingeben" /></p><div hidden class="load" id="load"></div></td>
                <tr>
                    <th scope="row"><label for="lists">Liste:</label></th>
                    <td>
                        <select class="form-control" id="lists">
                            <?php
                            if($widget != null){ echo "<option selected value='" . $widget->getListID() . "'>" . $widget->getListName() . "</option>"; } ?>
                        </select>
                    </td>
                </tr>
            </table>
            <table class="form-table" id="more" hidden>
                    <tr>
                        <th scope="row"><label for="email">Absender E-Mail-Adresse:</label></th>
                        <td><input type="email" id="email" name="email" class="regular-text" aria-describedby="mail-description" required="true" value="<?php echo $widget->getFromEmail(); ?>" placeholder="Absender E-Mail-Adresse" />
                            <p class="description" id="mail-description">Diese E-Mail-Adresse sollte eine Kontaktadresse für deinen KV sein (Bsp.: info@gruene-heidenheim.de)</p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="emailName">Absender Name:</label></th>
                        <td><input type="text" id="emailName" name="emailName" class="regular-text" aria-describedby="name-description" required value="<?php echo $widget->getFromName(); ?>" placeholder="Absender" />
                            <p class="description" id="name-description">Hier sollte der Name des KV's stehten (Bsp.: Grüne Heidenheim)</p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="subject">Betreff:</label></th>
                        <td><input type="text" id="subject" name="subject" class="regular-text" aria-describedby="subject-description" required value="<?php echo $widget->getSubject(); ?>" placeholder="Betreff" />
                            <p class="description" id="subject-description">Hier sollte der Betreff für die Begrüßungsmail stehen. (Bsp.: Anmeldung | Newsletter Grüne Heidenheim)</p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="text">Text:</label></th>
                        <td><textarea name="text" id="text" rows="5" cols="70"><?php echo $widget->getText(); ?></textarea>
                            <p class="description">Hier sollte ein individueller Begrüßungstext stehen, wie im Beispiel. (WICHTIG: den ?link-Tag nicht vergessen!)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="address">Adresse:</label></th>
                        <td>
                            <textarea name="address" id="address" rows="5" cols="30"><?php echo $widget->getAddress(); ?></textarea>
                            <p class="description">Hier kannst du die Adresse deines KV angeben.</p>
                        </td>
                    </tr>
                </div>
            </table>
            <p class="submit"><input disabled type="button" name="submit" id="submit_btn" class="button button-primary" value="Änderungen übernehmen"  /></p>
        </form>
    </div>
    <?php
}

function newsletter_add_menu(){
    add_option('green_newsletter_meta_field', 'description');
    add_options_page('Green Newsletter Plugin', 'Green Newsletter', 9, __FILE__, 'green_newsletter_option_page');
}

// add_action('admin_menu', 'newsletter_add_menu');

/**
 * Dadurch kann der Newsletter Shortcode [newsletter] auch
 * in der Seitenleiste benuzt werden.
 */
add_filter('widget_text','do_shortcode');

// Shortcode wird in Wordpress eingebunden
add_shortcode('newsletter', 'newsletter_shortcode');
// add_shortcode('unsubscribe', 'newsletter_unsubscribe');
// add_shortcode('subscribe', 'newsletter_subscribe');
//add_shortcode('edit_newsletter', 'edit_newsletter');

?>