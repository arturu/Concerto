<?php
/**
 * Index dell'applicazione
 * 
 * @version 0.2
 * @package index
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Concerto è un software modulare per lo sviluppo di applicazioni web.
 */

try {
    // carico l'Autoload delle classi
    require_once( 'app' . DIRECTORY_SEPARATOR . 'Autoloader.php' );

    // avvio del Core
    Concerto\Core::run();
    
    // restituisco i risultati
    Concerto\Response::run()->render();
    
} catch (Concerto\Eccezione $e) { // Oggetto Eccezione
    
    // stampa dell'errore
    $e->stampa($e);
    
}