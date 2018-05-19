<?php

require_once "App.class.php";
require_once __DIR__ . "/../lib/phpmailer/class.phpmailer.php";

class Processor{

    public static function addDataSet($email)
    {

        $app = new App();
        
        $client = $app->getRestClient();
        $key = $client->addNewReciever($email, $app->getGroupId());

        die(json_encode(array(
            "type" => "success",
            "text" => $key
        )));

        // self::send_mail($app, $email, "abcdefg");
        

    }

    public static function addFullDataSet($email, $surname, $lastname)
    {



    }

    private static function send_mail($app, $email, $key)
    {

        $mail = new PHPMailer();
        $mail->isHTML(true);
        $mail->CharSet = "utf-8";
        $mail->SetLanguage("de");
        $mail->SetFrom($app->get_from_email(), $app->get_from(), 0);
        // $mail->From = $app->get_from_email();
        // $mail->FromName = $app->get_from();
        $mail->AddAddress($email);
        $mail->Subject = $app->get_subject();

        $text = $app->parse_text($key);

        $mail->Body = nl2br($text);
        $mail->AltBody = strip_tags($text);

        var_dump($mail);
        die();

        if($mail->send())
        {
            die(json_encode(array(
                "type" => "success",
                "text" => $email
            )));
        } else{
            die(json_encode(array(
                "type" => "error",
                "text" => $mail->ErrorInfo
            )));
        }

    }

}

?>