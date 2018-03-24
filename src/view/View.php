<?php

class View {

    protected $router; 
    protected $content ="";

    function __construct($router){
        $this->router = $router;
    }

    function render(){
        $content = '<div class="content">';
        $content.= $this->content;
        $content .= '</div>';
        return $content;
    }

     // Permet de gérer les fichiers css 
    function initHeader($logged){
        echo '<!DOCTYPE html>';
        echo '<head>';
        echo '<title> Magic </title>';
        echo '<meta charset="utf-8" />';
        echo '<link href="'.$this->router->getCssURL("css/app.css").'" rel="stylesheet"/>';
        echo '</head>';
        echo '<ul id="menuHeader">';
        if($logged){
            echo '<li><a href="'.$this->router->getHomeURL().'"> Accueil </a></li>';
            echo '<li><a href="'.$this->router->getCardsURL().'"> Cartes </a></li>';
            echo '<li><a href="'.$this->router->getCollectionURL().'"> Mes Cartes </a></li>';
            echo '<li id="bouttonDeconnexion"><a href="'.$this->router->getLogoutURL().'" >Déconnexion</a></li>';
        }else{
            echo '<li><a href="'.$this->router->getHomeURL().'"> Accueil </a></li>';
            echo '<li><a href="'.$this->router->getCardsURL().'"> Cartes </a></li>';
            echo '<li><a href="'.$this->router->getLoginURL().'"> Connexion</a></li>';
        }
        echo '</ul>';
    }

    function makeHomePage(){
        $this->content.='<h1>Page d\'accueil</h1>';
        /*
        if(isLogged()){
            $this->content.= "<p> Bienvenue ".$_SESSION['name']."</p>";
            $logoutButton = new UserView($this->router);
            $logoutButton->makeLogoutButton();
            $this->content.=$logoutButton->render();
        }else{
            $this->content.='<a href="'.$this->router->getLoginURL().'"> Se connecter </a>';
        }*/
    }

    function makeUnauthorizedPage(){
        $this->content .= '<p> Vous n\'êtes pas autorisé à acceder à cette page</p>';
    }

    function makeInexistentPage(){
        $this->content .= '<p> Page innexistante</p>';
    }

    function makeLoginNeededPage($url){
        $this->content .= "<p> Vous devez vous connecter pour acceder à cette page : ";
        $connectView = new UserView($this->router);
        $connectView->makeLoginPage($url);
        $this->content = $connectView->render();
    }
}