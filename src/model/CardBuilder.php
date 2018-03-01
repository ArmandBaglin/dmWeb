<?php

class CardBuilder{

    private $datas;
    private $errors;
    private $storage;

    function __construct($datas,$storage){
        $this->datas = $datas;
        $this->storage = $storage;
    }

    function isValid(){
        if($this->datas == null){
            return false;
        }
        $this->errors = array();
        if(!$this->storage->isNameExtensionUnique($this->datas['name'],$this->datas['extension'])){
            array_push($this->errors,"Cette carte existe déjà");
        }
        if(count($this->errors) == 0){
            return true;
        }
        return false;
    }

    /**
     * Get the value of datas
     */ 
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * Set the value of datas
     *
     * @return  self
     */ 
    public function setDatas($datas)
    {
        $this->datas = $datas;

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