<?php

require_once 'HTTPType.enum.php';

class Client
{

    private $base = "https://rest.cleverreach.com/v2/";
    private $key;
    
    public function __construct($key)
    {
        $this->key = $key;
        $parse_uri = $_SERVER["DOCUMENT_ROOT"] . "/" . explode( 'wp-content',str_replace($_SERVER["DOCUMENT_ROOT"],'', str_replace('\\','/',__FILE__ ) ) )[0];
        require_once( $parse_uri . 'wp-load.php' );
    }

    public function addNewReciever($email, $groupId)
    {

        $response = $this->send_request(
            HTTPType::POST, 
            "groups/$groupId/receivers",
            array(
                "email" => $email,
                "registered" => time(),
                "deactivated" => "1",
                "source" => "Green Newsletter WP Plugin"
            )
        );

        return $this->add_new_key($response["id"]);
        
    }

    private function send_request($type, $url, $request)
    {
        $request = json_encode($request);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base . $url);
        $header = array();
        $header["content"] = "Content-Type: application/json";
        $header["token"] = "Authorization: Bearer " . $this->key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $response = curl_exec($ch);
        $info =  curl_getinfo($ch);

        $request_header_info = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        
        $response = json_decode($response, true);
        if(isset($response["error"])){
            die(json_encode(array(
                "type" => "error",
                "text" => $response["error"]["message"]
            )));
        }

        return $response;
        
    }

    private function add_new_key($id)
    {

        global $wpdb;

        $key = md5(uniqid(rand(), true));
        $table = $wpdb->prefix . 'green_newsletter_keys';

        try{

            $wpdb->insert($table, array(
                "key" => $key,
                "userId" => $id
            ));

            return $key;

        } catch(Exception $e){
            die(json_encode(array(
                "type" => "error",
                "text" => $e->getMessage()
            )));
        }


    }

}

?>