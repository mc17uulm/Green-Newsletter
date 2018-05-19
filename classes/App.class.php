<?php

require_once "Client.class.php";

class App
{

    private $api;
    private $groupId;
    private $from;
    private $fromEmail;
    private $subject;
    private $text;
    private $street;
    private $plz;
    private $city;
    private $rest;

    public function __construct()
    {

        $config = include __DIR__ . "\..\lib\config.php";

        $this->api = $config["apiKey"];
        $this->groupId = $config["groupId"];
        $this->from = $config["from"];
        $this->fromEmail = $config["fromEmail"];
        $this->subject = $config["subject"];
        $this->text = $config["text"];
        $this->street = $config["street"];
        $this->plz = $config["plz"];
        $this->city = $config["city"];
        $this->rest = new Client($this->api);

    }

    public function getApi()
    {
        return $this->api;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function get_from()
    {
        return $this->from;
    }

    public function get_from_email()
    {
        return $this->fromEmail;
    }

    public function get_subject()
    {
        return $this->subject;
    }

    public function get_text()
    {
        return $this->text;
    }

    public function get_street()
    {
        return $this->street;
    }

    public function get_plz()
    {
        return $this->plz;
    }

    public function get_city()
    {
        return $this->city;
    }

    public function print_address()
    {
        return $this->from . "<br>" . $this->street . " | " . $this->plz . " " . $this->city; 
    }

    public function parse_text($key)
    {

        $links = self::strpos_r($this->text, "?link");
        $sites = self::strpos_r($this->text, "?site");

        $link = "<a href='" . get_option("siteurl") . "/subscribe?key=$key'>Bestätigen</a>";

        $out = $this->text;

        for($i = 0; $i < count($links); $i++)
        {
            $out = substr($out, 0, $arr[$i]) . $link . substr($out, $arr[$i] + 5, strlen($out));
        }

        for($j = 0; $j < count($sites); $j++)
        {
            $out = substr($out, 0, $arr[$i]) . "<a href='" . get_option("siteurl") . "'>" . get_option("siteurl") . "</a>" . substr($out, $arr[$i] + 5, strlen($out));
        }

        return $out;
    }

    public function getRestClient()
    {
        return $this->rest;
    }

    public static function initalize_database()
    {

        global $wpdb;

        $table = $wpdb->prefix . 'green_newsletter_keys';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table (
                `ID` INT NOT NULL AUTO_INCREMENT,
                `key` VARCHAR(255) NOT NULL,
                `userId` INT NOT NULL,
                PRIMARY KEY (`ID`)
                )$charset_collate;";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);        

    }

    public static function strpos_r($haystack, $needle)
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

}

?>