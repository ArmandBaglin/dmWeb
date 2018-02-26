<?php

class UserView extends View{

    function __construct($router){
        parent::__construct($router);
    }

    function render(){
        return $this->content;
    }

    function makeUserCreationPage($errors = array()){
        $this->content.="<h1>S'inscrire</h1>";
        $this->content.='<form action="'.$this->router->getUserCreationURL().'" method=POST>';
        $this->content.='<label> Nom : <input type="text" name="name" id="name"/></label>';
        $this->content.='<label> Mot de passe : <input type="password" name="password" id="password"/></label>';
        $this->content.='<input type="submit"/>';
        $this->content.='</form>';
        if(!empty($errors)){
            foreach ($errors as $key => $value) {
                # code...
                $this->content.=$value.'<br>';
            }
        } 
    }

    function makeLoginPage($error = null){
        $this->content.="<h1>Connexion</h1>";
        $this->content.='<form action="'.$this->router->getLoginURL().'" method=POST>';
        $this->content.='<label> Nom : <input type="text" name="name" id="name"/></label>';
        $this->content.='<label> Mot de passe : <input type="password" name="password" id="password"/></label>';
        $this->content.='<input type="submit" value="Se connecter"/>';
        $this->content.='</form>';
        $this->content.=$error;
    }

    function makeLogoutButton(){
        $this->content .= '<a href="'.$this->router->getLogoutURL().'"><button>Déconnexion</button></a>';
    }

}