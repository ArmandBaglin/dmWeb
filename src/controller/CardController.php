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

    function showAllCards($extension ,$logged = null){

        $extensionID = $this->storage->getExtensionIDByName(replaceUnderscoreBySpace($extension));
        if(!$extensionID){
            $this->showExtensionList();
        }else{
            if($logged){
                $cards = $this->storage->readUserCardsByExtension($extensionID,$_SESSION['name']);
            }else{
                $cards = $this->storage->readCardsByExtension($extensionID);
            }
            $this->view->makeExtensionForm($this->storage->readAllExtension(),replaceUnderscoreBySpace($extension));
            $this->view->makeTableWithCards($cards,$logged,ucwords(strtolower(replaceUnderscoreBySpace($extension))));
        }

    }

    function addCardToUser($data){
        if(key_exists('extension',$data)){
            $extension = replaceSpaceByUnderscore(strtoupper($data['extension']));
            unset($data['extension']);
        }
        foreach ($data as $key => $value) {
            # code...
            $this->storage->addCardToUser($_SESSION['name'],$key,$value);
        }
        if(isset($extension)){
            $this->showAllCards($extension,true);
        }else{
            $this->showExtensionList();
        }
    }
}