<?php

class Card{

    private $id;
    private $name;
    private $rarity;
    private $extension;
    private $type;

    function __construct($id,$name,$rarity,$extension,$type){
        $this->id = $id;
        $this->name= $name;
        $this->rarity = $rarity;
        $this->extension =$extension;
        $this->type = $type;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of rarity
     */ 
    public function getRarity()
    {
        return $this->rarity;
    }

    /**
     * Set the value of rarity
     *
     * @return  self
     */ 
    public function setRarity($rarity)
    {
        $this->rarity = $rarity;

        return $this;
    }

    /**
     * Get the value of extension
     */ 
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of extension
     *
     * @return  self
     */ 
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}