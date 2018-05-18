<?php

class Shortcode
{

    public static function render($mode = "standard", $title = false)
    {
        
        $full = false;
        $captcha = false;
        $header = !$title ? false : true;

        switch($mode)
        {

            case "full":
                $full = true;
                break;

            case "standard":
                break;

            case "captcha":
                $captcha = true;
                break;

        }

        ob_start();
        include __DIR__ . '/../lib/template.php';
        echo ob_get_clean();

    }

}

?>