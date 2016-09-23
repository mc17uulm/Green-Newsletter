<?php

/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 23.09.2016
 * Time: 20:38
 */
class key{

    private $id;
    private $key;
    private $userID;

    public function __construct($id, $key, $userID){
        $this->id = $id;
        $this->key = $key;
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }



}