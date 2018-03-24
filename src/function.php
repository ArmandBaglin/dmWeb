<?php


// Permet de savoir si un utilisateur est connecté
function isLogged(){
    if(key_exists('name',$_SESSION) && key_exists('role',$_SESSION)){
        return true;
    }
    return false;
}

/* Vérifie si il y a un utilisateur de connecté et si il est autorisé à acceder à une page 
* En gros, les admin ont 1 et les users normaux ont 2 
* Donc pour les pages réservées aux admin, on apelle isAuthorized(1) et l'user normal ne peux pas passer
*/
function isAuthorized($role){
    if(!isLogged()){
        return false;
    }
    if($_SESSION['role'] > $role){
        return false;
    }
    return true;
}

function replaceSpaceByUnderscore($str){
    $tab = explode(' ',$str);
    $str = implode($tab,"_");
    return $str;
}

function replaceUnderscoreBySpace($str){
    $tab = explode('_',$str);
    $str = implode($tab," ");
    return $str;
}