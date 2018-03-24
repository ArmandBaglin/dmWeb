<?php
class CardView extends View{

    function __construct($router){
        parent::__construct($router);
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
        $this->content.= '<ul id="ExtensionList">';
        foreach ($extensions as $key => $value) {
            # code...
            $this->content.= '<li> <a href="'.$this->router->getCardsURL().'/'.replaceSpaceByUnderscore($value['extension_name']).'">'.ucwords(strtolower($value['extension_name'])).'</a></li>';
        }
        $this->content.= '</ul>';
    }


    function makeExtensionForm($list,$currentExtension=''){
        $this->content .= '<form action='.$this->router->getCardsURL().' method="POST">';
        $this->content .= '<select name="extension">';
        foreach ($list as $key => $value) {
            # code...
            if($value['extension_name']===$currentExtension){
                $this->content .= '<option selected value="'.$value['extension_name'].'">'.$value['extension_name'].'</option>';
            }else{
                $this->content .= '<option value="'.$value['extension_name'].'">'.$value['extension_name'].'</option>';
            }
        }
        $this->content .= '</select>';
        $this->content .= '<input type="submit" value="Chercher" />';
        $this->content .= '</form>';
    }

    function makeTableWithCards($cards,$logged,$extensionName){
        $this->content .= '<table id="card_table">';
        $this->content .= '<thead>';
        $this->content .= '<tr><th colspan="6"> Carte de l\'extension: '.$extensionName.'</th></tr>';
        $this->content .= '<tr>';
        $this->content .= '<th></th>';
        $this->content .= '<th> Nom </th>';
        $this->content .= '<th> Mana </th>';
        $this->content .= '<th> Type </th>';
        $this->content .= '<th> Rareté </th>';
        if($logged){
            $this->content .= '<th> Mes Cartes </th>';
        }
        $this->content .= '</thead>';
        $this->content .= '<tbody>';
        $this->content .= '</tr>';
        $c = $cards[0];
        $colors = $cards[1];
        if($logged){
            $userCards = $cards[2];
        }
        foreach ($c as $key => $card) {
            # code...
            $this->content .= '<tr>';
            $this->content .= '<th>'. ($key+1) .'</th>';
            $this->content .= '<td>'.$card->getName().'</td>';
            $this->content .= '<td>';
            foreach ($colors[$key] as $key => $value) {
                # code...
                $this->content .= $value['cost'].' '.$this->getColorImage($value['color']);
            }  
            $this->content .= '</td>';        
            $this->content .= '<td>'.$card->getType().'</td>';
            $this->content .= '<td>'.$card->getRarity().'</td>';
            if($logged){
                $this->content .= '<td>';
                $this->content .= '<form method="POST" action='.$this->router->getAddCardURL().'>';
                $this->content .= '<input id="table_input" type="text" value="'.$userCards[$key].'" name="'.$card->getId().'" />';
                $this->content .= '<input type="hidden" name="extension" value="'.$extensionName.'"/>';
                $this->content .= '<input id="table_modify_button" type="submit" value="modifier">';
                $this->content .= '</form>';
                $this->content .= '</td>';
            }
            $this->content .= '</tr>';
        }
        $this->content .= '</tbody>';
        $this->content .= '<tfoot>';
        $this->content .= '<tr>';
        $this->content .= '<th></th>';
        $this->content .= '<th> Nom </th>';
        $this->content .= '<th> Mana </th>';
        $this->content .= '<th> Type </th>';
        $this->content .= '<th> Rareté </th>';
        if($logged){
            $this->content .= '<th> Mes Cartes </th>';
        }
        $this->content .= '</tfoot>';
        $this->content .= '</table>';
    }

    function getColorImage($colorName){
        switch ($colorName) {
            case 'BLANC':
                
                return '<img alt="blanc" class="mana_img" src='.$this->router->getImage('blanc.svg').'/>';
                break;
            case 'ROUGE':
                return '<img alt="rouge" class="mana_img" src='.$this->router->getImage('rouge.svg').'/>';
                break;

            case 'NOIR':
                return '<img alt="noir" class="mana_img" src='.$this->router->getImage('noir.svg').'/>';
                break;    

            case 'BLEU':
                return '<img alt="bleu" class="mana_img" src='.$this->router->getImage('bleu.svg').'/>';
                break; 

            case 'INCOLORE':
                return '<img alt="incolore" class="mana_img" src='.$this->router->getImage('incolore.svg').'/>';
                break; 

            case 'VERT':
                return '<img alt="vert" class="mana_img" src='.$this->router->getImage('vert.svg').'/>';
                break; 
            
            default:
                # code...
                break;
        }
    }
}