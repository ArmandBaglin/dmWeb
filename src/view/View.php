<?php

class View {

    protected $router; 
    protected $content ="";

    function __construct($router){
        $this->router = $router;
    }

    function render(){
        return $this->content;
    }

     // Permet de gérer les fichiers css 
    function initHeader(){
        echo '<head>';
        echo '<meta charset="utf-8" />';
        echo '<link href="'.$this->router->getCssURL("css/app.css").'" rel="stylesheet"/>';
        echo '</head>';
    }

    function makeHomePage(){
        $this->content.='<h1>Page d\'accueil</h1>';
        if(isLogged()){
            $this->content.= "<p> Bienvenue ".$_SESSION['name']."</p>";
            $logoutButton = new UserView($this->router);
            $logoutButton->makeLogoutButton();
            $this->content.=$logoutButton->render();
        }else{
            $this->content.='<a href="'.$this->router->getLoginURL().'"> Se connecter </a>';
        }
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