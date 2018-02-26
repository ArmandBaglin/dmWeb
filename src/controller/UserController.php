<?php
require_once 'model/UserBuilder.php';

class UserController{

    private $view;
    private $storage;

    function __construct($view,$storage){
        $this->view = $view;
        $this->storage = $storage;
    }

    function createUser($data){
       $builder = new UserBuilder($data,$this->storage);
        if($builder->isValid()){
            $user = new User($data['name'],password_hash($data['password'],PASSWORD_BCRYPT),2);
            $this->storage->create($user);
            $this->view->makeLoginPage();
        }else{
            // Remet le formulaire avec les erreurs 
            $this->view->makeUserCreationPage($builder->getErrors());
        }
    }

    function login($data){
        if(key_exists('name',$_SESSION) && key_exists('role',$_SESSION)){
            // Utilisateur déjà connecté redirigé vers page d'accueil
            $this->view->makeHomePage();
        }else{
            if(key_exists('name',$data) && key_exists('password',$data)){
                // Formulaire de connexion déjà rempli
                $user = $this->storage->read($data['name']);
                if(!$user){
                    $error = 'Utilisateur inconnu';
                    $this->view->makeLoginPage($error);
                }else{
                    if(!password_verify($data['password'],$user->getPassword())){
                        $error = 'Mauvais mot de passe';
                        $this->view->makeLoginPage($error);
                    }else{
                        $_SESSION['name'] = $user->getName();
                        $_SESSION['role'] = $user->getRole();
                        $this->view->makeHomePage();
                    }
                }
            }else{
                $this->view->makeLoginPage();
            }
        }
    }

    function logout(){
        unset($_SESSION['name']);
        unset($_SESSION['role']);
        $this->view->makeHomePage();
    }
}