<?php

class UserBuilder{

    private $data;
    private $storage;
    private $errors;

    function __construct($data,$storage){
        $this->data = $data;
    }

    function isValid(){

        if($this->data == null){
            return false;
        }
        $this->errors = array();
        if(!key_exists("name",$this->data)){
            array_push($this->errors,"Il faut renseigner un nom d'utilisateur");
            if(!$this->storage->userNameAvailable($data['name'])){
                array_push($this->errors,"Nom d'utilisateur indisponible");
            }
        }
        if(!key_exists("password",$this->data)){
            array_push($this->errors,"Il faut renseigner un mot de passe");
        }else{
            $pattern = "/[a-zA-Z0-9]{8,}/";
            if(!preg_match($pattern,$this->data['password'])){
                array_push($this->errors,"Mot de passe invalide");
            }
        }
        if(count($this->errors) == 0){
            return true;
        }
        return false;
       
    }

    /**
     * Get the value of data
     */ 
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */ 
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of errors
     */ 
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set the value of errors
     *
     * @return  self
     */ 
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }
}