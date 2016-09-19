<?php

/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 18.09.2016
 * Time: 22:04
 */
class widget {

    private $apiKey;
    private $listID;
    private $listName;
    private $fromEmail;
    private $fromName;
    private $subject;
    private $text;
    private $address;

    public function __construct($apiKey, $listID, $listName, $fromEmail, $fromName, $subject, $text, $address){
        $this->apiKey = $apiKey;
        $this->listID = $listID;
        $this->listName = $listName;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->subject = $subject;
        $this->text = $text;
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return mixed
     */
    public function getListID()
    {
        return $this->listID;
    }

    /**
     * @param mixed $listID
     */
    public function setListID($listID)
    {
        $this->listID = $listID;
    }

    /**
     * @return mixed
     */
    public function getListName()
    {
        return $this->listName;
    }

    /**
     * @param mixed $listName
     */
    public function setListName($listName)
    {
        $this->listName = $listName;
    }

    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param mixed $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }




}