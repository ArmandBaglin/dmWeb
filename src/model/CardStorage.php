<?php

require_once 'Card.php';

class CardStorage{

    private $db;

    function __construct($db){
        $this->db = $db;
    }

    function create ($card){
        $insert = $this->db->prepare("INSERT INTO card(card_name,card_rarity,card_extension,card_type) values (:name,:rarity,:extension,:type)");
        $res = $insert->execute(array(
            'name' => $card->getName(),
            'rarity' => $card->getRarity(),
            'extension' => $card->getExtension(),
            'type' => $card->getType(),
        ));
        $id = $this->db->lastInsertId();
        return $id;
    }

    function readById($id){
        $query = $this->db->prepare("SELECT * FROM card where card_id = :id");
        $res = $query->execute(array(
            'id' => $id,
        ));
        $card = $query->fetch();
        if($card){
            return new Card($card['card_id'],$card['card_name'],$card['card_rarity'],$card['card_extension'],$card['card_type']);
        }else{
            return false;
        }
    }

    function read($extension,$name){
        $query = $this->db->prepare("SELECT * FROM card where card_name like :id and card_extension like :extension");
        $res = $query->execute(array(
            'name' => $name,
            'extension' => $extension,
        ));
        $card = $query->fetch();
        if($card){
            return new Card($card['card_id'],$card['card_name'],$card['card_rarity'],$card['card_extension'],$card['card_type']);
        }else{
            return false;
        }
    }

    function realAll(){
        $query = $this->db->query("SELECT * from card");
        $card =array();
        foreach ($query->fetchAll() as $key => $value) {
            # code...
            array_push($card, new Card($card['card_id'],$card['card_name'],$card['card_rarity'],$card['card_extension'],$card['card_type']));
        }
        return $card;
    }

    function getExtensionName($id){
        $query = $this->db->prepare("SELECT  extension_name from extension where extension_id = :id");
        $query->execute(array(
            'id' => $id,
        ));
        $name = $query->fetch();
        if($name){
            return $name[0];
        }else{
            return false;
        }
    }

    function getRarityName($id){
        $query = $this->db->prepare("SELECT  rarity_name from rarity where rarity_id = :id");
        $query->execute(array(
            'id' => $id,
        ));
        $name = $query->fetch();
        if($name){
            return $name[0];
        }else{
            return false;
        }
    }

    function readAllExtension(){
        $query = $this->db->query("SELECT  * from extension");
        return $query->fetchAll();
    }

    function readAllRarity(){
        $query = $this->db->query("SELECT  * from rarity");
        return $query->fetchAll();
    }

    function readAllColor(){
        $query = $this->db->query("SELECT  * from color");
        return $query->fetchAll();
    }

    function readCardColors($cardID){
        $query = $this->db->prepare("SELECT  * from card_color
                                    join color on card_color.card_color_color  = color.color_id 
                                    where card_color_card = :id");
        $query->execute(array(
            'id' => $cardID,
        ));
        $return = array();
        foreach ($query->fetchAll() as $key => $value) {
            # code...
            array_push($return,array("color" => $value['color_name'],"cost" =>$value['card_color_cost']));
        }
        return $return;
    }

    function delete($card){
        $query = $this->db->prepare("delete from card where id = :id");
        $a = $query->execute(array(
            'id' => $card->getId(),
        ));
        return $a;
    }

    function addColor($cardID,$colorId,$nb){
        $query = $this->db->prepare("INSERT INTO card_color(card_color_card,card_color_color,card_color_cost) values (:cardID,:colorID,:nb)");
        $query->execute(array(
            "cardID" => $cardID,
            "colorID" => $colorId,
            "nb" => $nb,
        ));
        var_dump($cardID,$colorId,$nb);
        var_dump($query);
    }

    // Permet de vérifier que l'on ne peux pas rentrer une carte en double
    // Plusieurs cartes peuvent avoir le même nom mais elles doivent être dans des extensions différentes
    function isNameExtensionUnique($name,$extension){
        $query = $this->db->prepare("SELECT count(*) from card where card_name = :name and card_extension = :extension");
        $res = $query->execute(array(
            "name" => $name,
            "extension" => $extension,
        ));
        return $query->fetch()[0] == 0;
        
    }

    function readExtension($name){
        $query = $this->db->prepare("SELECT * FROM extension where extension_name like :name");
        $res = $query->execute(array(
            "name" => $name,
        ));
        $ext = $query->fetch();
        if($ext){
            return $ext;
        }else{
            return false;
        }

    }

    /*
    Permet de récupérer des cartes sous forme d'objet
    Récupère toutes les cartes d'une extension 
    ExtensionID correspond à un entier et non le nom de l'extension
    */
    function readCardsByExtension($ExtensionID){
        $query = $this->db->prepare("SELECT * FROM card 
                                    where card_extension = :id");
        $res = $query->execute(array(
            "id" => $ExtensionID,
        ));
        $cards = array();
        $colors = array();
        foreach ($query->fetchAll() as $key => $card) {
            # code...
            $extension = $this->getExtensionName($card['card_extension']);
            $rarity = $this->getRarityName($card['rarity']);
            array_push($cards, new Card($card['card_id'],$card['card_name'],$rarity,$extension,$card['card_type']));
            array_push($colors,$this->readCardColors($card['card_id']));
        }
        return array($cards,$colors);
    }

    /*
    Permet de récupérer des cartes sous forme d'objet
    Récupère toutes les cartes d'un utilisateur pour une extension donnée
    ExtensionID correspond à un entier et non le nom de l'extension
    */
    function readUserCardsByExtension($ExtensionID,$userName){
        $query = $this->db->prepare("SELECT * FROM card
                                     where card_extension = :id");
        $res = $query->execute(array(
            "id" => $ExtensionID,
        ));     
        $a = $this->db->prepare("SELECT user_id from users where user_name like :name");
        $a->execute(array(
            "name" => $userName,
        ));
        $userId = $a->fetch()[0];
        $cards = array();
        $colors = array();
        $userCards = array();
        foreach ($query->fetchAll() as $key => $card) {
            # code...
            $extension = $this->getExtensionName($card['card_extension']);
            $rarity = $this->getRarityName($card['card_rarity']);
            array_push($cards, new Card($card['card_id'],$card['card_name'],$rarity,$extension,$card['card_type']));
            array_push($colors,$this->readCardColors($card['card_id']));
            $userCard = $this->db->prepare("SELECT own_amount from own_card
                                            where own_card = :card and own_user = :user");
            $userCard->execute(array(
                "card" => $card['card_id'],
                "user" => $userId,
            ));
            $fetch = $userCard->fetch();
            if(!$fetch){
                array_push($userCards,"0");
            }else{
                array_push($userCards,$fetch[0]);
            }
        }
        return array($cards,$colors,$userCards);                       
    }

    /*
    Fonction qui permet d'ajouter une carte à la collection d'un joueur
    Le nombre de carte possedé est représenté par amount
    */
    function addCardToUser($userName,$card_id,$amount){
        $a = $this->db->prepare("SELECT user_id from users where user_name like :name");
        $a->execute(array(
            "name" => $userName,
        ));
        $user_id = $a->fetch()[0];
        $query = $this->db->prepare("INSERT INTO OWN_CARD(own_user,own_card,own_amount) values (:user,:card,:amount) on duplicate key update own_amount = :amount");
        $query->execute(array(
            "user" => $user_id,
            "card" => $card_id,
            "amount" => $amount,
        ));
    }

    /* Permet de récupérer l'ID d'une extension gràce à son nom */
    function getExtensionIDByName($name){
        $a = $this->db->prepare("SELECT extension_id from extension where extension_name like :name");
        $a->execute(array(
            "name" => $name,
        ));
        $id = $a->fetch();
        if($id){
            return $id[0];
        }else{
            return false;
        }
    }
}