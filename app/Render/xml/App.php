<?php
/**
 * Gestisce il render in XML
 * 
 * @version 0.1
 * @package Render
 * @class App
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo XML.
 */

namespace Render\xml;

use Concerto\Response;
use Concerto\Config;
use Concerto\Eccezione;

class App {
    
    // il nome della cartella dove è salvata la vista della specifica App
    // i metodi che utilizzano questo attributo 
    // caricheranno da NomeApp/vista/xml/vista.php da caricare
    var $render = 'xml';
    
    public function __construct() {
        
        header ("Content-Type: text/xml");
        echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        
        $this->render();
    }
    
    public function render() {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // recupero il percorso della vista
        $vista = 
            $impostazioni['Concerto\App']['path_app'] .DIRECTORY_SEPARATOR .
            'viste'. DIRECTORY_SEPARATOR . $this->render . DIRECTORY_SEPARATOR .
            Response::run()->get_vista() . '.php';
        
        // controllo se la vista esiste
        if (file_exists($vista) ) {
            // inserisco dentro $output il responde
            $output = Response::run()->get_response();
            
            // includo la vista che utilizzerà $output per presentare il risultato
            require_once $vista;
        }
        else
            throw new Eccezione('Errore non riesco a trovare: '.$vista);
    }
    
}