<?php
class CardView extends View{

    function __construct($router){
        parent::__construct($router);
    }

    function render(){
        return $this->content;
    }

    function makeCardCreationPage($errors,$extension,$rarity,$colors){
        $this->content .= '<form method=POST action='.$this->router->getCardCreationURL().'>';
        $this->content .= '<label> Nom : <input type="text" name="name" id="name"/></label>';
        $this->content .= '<label> Type : <input type="text" name="type" id="type"/></label>';
        // Liste déroulante sur l'extension de la carte
        $this->content .= '<label> Extension : ';
        $this->content .= '<select id="extension" name="extension">';
        foreach ($extension as $key => $value) {
            $this->content .= '<option value ='.$value['extension_id'].'>'.$value['extension_name'].'</option>';
        }
        $this->content .= '</select></label>';
        // Liste déroulante sur la rareté de la carte
        $this->content .= '<label> Rareté : ';
        $this->content .= '<select id="rarity" name="rarity">';
        foreach ($rarity as $key => $value) {
            $this->content .= '<option value ='.$value['rarity_id'].'>'.$value['rarity_name'].'</option>';
        }
        $this->content .= '</select></label>';

        
        $this->content .= '<br><label> Mana nécessaire :';
        foreach ($colors as $key => $value) {
            $this->content .= '<br><label> '.$value['color_name'].' : <input type="number" value=0 name="'.$value['color_name'].'" /> </label>';
        }
        $this->content .= '</label>';
        $this->content .= '<input type="submit" value="Créer" />';
        $this->content .= '</form>';

        if(!empty($errors)){
            foreach ($errors as $key => $value) {
                # code...
                $this->content.=$value.'<br>';
            }
        } 
    }

    function makeExtensionList($extensions){
        $this->content.= '<ul>';
        foreach ($extensions as $key => $value) {
            # code...
            $this->content.= '<li> <a href="'.$this->router->getExtensionURL(replaceSpaceByUnderscore($value['extension_name'])).'">'.$value['extension_name'].'</a></li>';
        }
        $this->content.= '</ul>';
    }

    function makeExtensionPage($extension,$cards){
        var_dump($extension);
        var_dump($cards);
    }
}