<?php
/**
 * Gestore delle eccezioni
 * 
 * @version 0.1
 * @package Concerto
 * @class Eccezione
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Gestore delle Eccezioni
 */

namespace Concerto;

// usiamo "\" (root namespace) per caricare Exception che è una classe PHP
class Eccezione extends \Exception {
    
    /**
     * Metodo che stampa l'errore
     * 
     * @param Object $e l'oggetto Eccezione
     */
    function stampa($e){
        echo 'Exception: ',  $e->getMessage(), "\n";
    }
    
}