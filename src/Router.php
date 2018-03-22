<?php

require_once 'view/View.php';
require_once 'view/UserView.php';
require_once 'view/CardView.php';
require_once 'controller/UserController.php';
require_once 'controller/CardController.php';
require_once 'controller/CollectionController.php';
require_once 'model/UserStorage.php';
require_once 'model/CardStorage.php';
require_once 'model/CollectionStorage.php';

class Router{

    private $rep = "/Magic/dmWeb/magic.php";

	public function main($storage){
        session_start();

        // Initialisation des vues
        $userView = new UserView($this);
        $cardView = new CardView($this);

        // Initialisation des controleurs 
        $userController = new UserController($userView,new UserStorage($storage));
        $cardController = new CardController($cardView,new CardStorage($storage));
        $collectionController = new CollectionController($cardView, new CollectionStorage($storage));

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

                    case 'newCard' :
                        if(isAuthorized(1)){
                            $cardController->CreateCard($_POST);
                            $mainView = $cardView;
                        }else{
                            $mainView->makeUnauthorizedPage();
                        }
                                break;
                                
                    case 'extensions' : 
                        if(isset($path[2])){
                            $cardController->showExtension(replaceUnderscoreBySpace($path[2]));
                            $mainView = $cardView;
                        }else{
                            $cardController->showExtensionList();
                            $mainView = $cardView;
                        }
                        break;

                    case 'collection' :
                        if(isLogged()){
                            $collectionController->showCollection($_SESSION['name']);
                            $mainView = $cardView;
                        }else{
                            $mainView->makeLoginNeededPage($this->getCollectionURL());
                        }
                        break;
                    case 'cards' : 
                        $cardController->showAllCards($_POST);
                        $mainView = $cardView;
                        break;
                    default :
                             $mainView->makeInexistentPage();
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

    function getCardsURL(){
        return $this->rep.'/cards';
    }
    function getCollectionURL(){
        return $this->rep.'/collection';
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

    function getCardCreationURL(){
        return $this->rep.'/newCard';
    }

    function getExtensionURL($extension){
        return $this->rep.'/extensions/'.$extension;
    }

    function getImage($name){
        return $this->rep.'/../src/images/'.$name;
    }

}
?>