<?php


class CollectionStorage{

    private $db;
    private $cardStorage;

    function __construct($db){
        $this->db = $db;
        $this->cardStorage = new CardStorage($db);
    }

    function getCards($userName){
        $query = $this->db->prepare("SELECT * FROM users 
                                    join own_card on users.user_id = own_card.own_user
                                    join card on own_card.own_card = card.card_id
                                    where user_name = :name");
        $res = $query->execute(array(
            'name' => $userName,
        ));
        $userID = $query->fetchAll();
        var_dump($userID);
    }
}