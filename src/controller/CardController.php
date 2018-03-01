<?php

require_once 'model/CardBuilder.php';
class CardController{

    private $view;
    private $storage;

    function __construct($view,$storage){
        $this->view = $view;
        $this->storage = $storage;
    }

    function createCard($data){
        $builder = new CardBuilder($data,$this->storage);
        if($builder->isValid()){
            //insertion
            var_dump($data);
            $card = new Card(null,$data['name'],$data['rarity'],$data['extension'],$data['type']);
            $cardId = $this->storage->create($card);
            $colors = $this->storage->readAllColor();
            foreach ($colors as $key => $value) {
                # code...
                if($data[$value['color_name']] != 0){
                    $this->storage->addColor($cardId,$value['color_id'],$data[$value['color_name']]);
                }
            }
            $this->view->makeHomePage();
        }else{
            $this->view->makeCardCreationPage($builder->getErrors(),$this->storage->readAllExtension(),$this->storage->readAllRarity(),$this->storage->readAllColor());
        }
    }

    function showExtensionList(){
        $extensions = $this->storage->readAllExtension();
        $this->view->makeExtensionList($extensions);
    }

    function showExtension($extensionName){
        $extension = $this->storage->readExtension($extensionName);
        if(!$extension){
            $this->showExtensionList();
        }else{
            $cards = $this->storage->readCardsByExtension($extension['extension_id']);
            $this->view->makeExtensionPage($extension,$cards);
        }
    }
}