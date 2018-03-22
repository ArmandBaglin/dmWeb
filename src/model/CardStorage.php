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
            array_push($return,array("color" => $value['color_name'],"cost" =>$value['card_color_color']));
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
            array_push($cards, new Card($card['card_id'],$card['card_name'],$card['card_rarity'],$card['card_extension'],$card['card_type']));
            array_push($colors,$this->readCardColors($card['card_id']));
        }
        return array($cards,$colors);
    }
}