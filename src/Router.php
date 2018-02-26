<?php

require_once 'view/View.php';
require_once 'view/UserView.php';
require_once 'controller/UserController.php';
require_once 'model/UserStorage.php';

class Router{

    private $rep = "/dmWeb/magic.php";

	public function main($storage){
        session_start();

        // Initialisation des vues
        $userView = new UserView($this);

        // Initialisation des controleurs 
        $userController = new UserController($userView,new UserStorage($storage));

        /*
        *   Vue globale, en fonction de ce qui est demandé par l'utilisateur, elle sera initialisée avec une vue plus spécialisée
        *   Cela permet de disperser les fonctions sur plusieurs page pour plus de lisibilité
        */
        $mainView = new View($this);

        try{
            if(array_key_exists("PATH_INFO", $_SERVER)){
                $path = explode('/',$_SERVER['PATH_INFO']);
                switch($path[1]){
                    case 'users' : 
                                switch ($path[2]) {
                                    case 'new':
                                        $userController->createUser($_POST);
                                        $mainView = $userView;
                                        break;
                                    
                                    default:
                                        $mainView->makeInexistentPage();;
                                        break;
                                }
                                break;

                    case 'home': $mainView->makeHomePage();
                                break;
                    
                    case 'connexion' : 
                        $userController->login($_POST);
                        $mainView = $userView;
                                break;

                    case 'deconnexion' : 
                        $userController->logout();
                        $mainView = $userView;
                                break;

                    default :
                             $mainView->makeHomePage();
                            break;
                }

            }else{
                $mainView->makeHomePage();
            }
        }catch(Exception $e){
            echo $e.getMessage();
        }
            
        $mainView->initHeader();
       echo $mainView->render();
    }
    
    function getUserCreationURL(){
        return $this->rep.'/users/new';
    }

    function getLoginURL(){
        return $this->rep.'/connexion';
    }
    function getLogoutURL(){
        return $this->rep.'/deconnexion';
    }
    function getHomeURL(){
        return $this->rep.'/home';
    }

    function getCssURL($file){
        return $this->rep.'/../src/'.$file;
    }

}
?>