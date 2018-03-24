<?php




require_once "User.php";

class UserStorage{

    private $db;

    function __construct($db){
        $this->db = $db;
    }

    function create(User $u){
        $insert = $this->db->prepare("INSERT INTO users(user_name,user_password,user_role) values (:name,:password,:role)");
        $res = $insert->execute(array(
            'name' => $u->getName(),
            'password' => $u->getPassword(),
            'role' => $u->getRole(),
        ));
        $id = $this->db->lastInsertId();
        return $id;
    }

    function read($name){
        $query = $this->db->prepare("SELECT * FROM users where user_name = :name");
        $res = $query->execute(array(
            'name' => $name,
        ));
        $user = $query->fetch();
        if($user){
            return new User($user['user_name'],$user['user_password'],$user['user_role']);
        }else{
            return false;
        }
    }

    function realAll(){
        $query = $this->db->query("SELECT * from users");
        $users =array();
        foreach ($query->fetchAll() as $key => $value) {
            # code...
            array_push($users,new User($value['user_name'],$value['user_password'],$value['user_role']));
        }
        return $users;
    }

    function delete(User $u){
        $query = $this->db->prepare("delete from users where id = :id");
        $a = $query->execute(array(
            'id' => $u->getId(),
        ));
        return $a;
    }

    function userNameAvailable($name){
        $query = $this->db->prepare("SELECT count() from users where user_name=:name");
        $res= $query->execute(array(
            'name' => $name,
        ));
        return $res->fetch()[0];
    }
}