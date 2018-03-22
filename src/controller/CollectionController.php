<?php

class CollectionController{

    private $view;
    private $storage;

    function __construct($view,$storage){
        $this->view = $view;
        $this->storage = $storage;
    }
    
    function showCollection($userName){
        $this->storage->getCards($userName);
        var_dump($user);
    }

}

?>