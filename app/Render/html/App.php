<?php
/**
 * Gestisce il render in HTML
 * 
 * @version 0.1
 * @package Render
 * @class App
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo HTML.
 */

namespace Render\html;

class App extends \Render\xml\App {
    
    // il nome della cartella dove è salvata la vista della specifica App
    // i metodi che utilizzano questo attributo 
    // caricheranno da NomeApp/vista/html/vista.php da caricare
    var $render = 'html';
    
    public function __construct() {
        
        // genero la l'output
        $this->render();

    }
    
}