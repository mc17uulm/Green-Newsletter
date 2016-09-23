<?php

/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 18.09.2016
 * Time: 22:04
 */
class widget {

    private $ID;
    private $apiKey;
    private $url;
    private $listID;
    private $listName;
    private $fromEmail;
    private $fromName;
    private $subject;
    private $text;
    private $address;

    public function __construct($ID, $apiKey, $listID, $listName, $fromEmail, $fromName, $subject, $text, $address){
        $this->ID = $ID;
        $this->apiKey = $apiKey;
        $this->url = "http://api.cleverreach.com/soap/interface_v5.1.php?wsdl";
        $this->listID = $listID;
        $this->listName = $listName;
        if($fromEmail == null){
            $this->fromEmail = "info@gruene.de";
        } else{
            $this->fromEmail = $fromEmail;
        }
        if($fromName == null){
            $this->fromName = "Grüne";
        } else{
            $this->fromName = $fromName;
        }
        if($subject == null){
            $this->subject = "Anmeldung Newsletter | Grüne";
        } else{
            $this->subject = $subject;
        }
        if($text == null){
            $this->text = "Vielen Dank für Ihr Interesse an unserem Newlsetter. Um Ihre Registrierung abzuschließen, klicken Sie bitte auf folgenden Link: ?link \r\n\r\nSollten Sie sich nicht auf gruene-heidenheim.de für den Newsletter angemeldet haben, können Sie diese E-Mail ignorieren!";
        } else{
            $this->text = $text;
        }
        if($address == null){
            $this->address = "KV Test\r\nMusterstraße 10\r\n89518 Heidenheim";
        } else{
            $this->address = $address;
        }
    }

    public function getID(){
        return $this->ID;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getURL(){
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getListID()
    {
        return $this->listID;
    }

    /**
     * @return mixed
     */
    public function getListName()
    {
        return $this->listName;
    }

    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }




}