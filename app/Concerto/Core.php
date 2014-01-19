<?php
/**
 * Il core dell'applicazione
 * 
 * @version 0.1
 * @package Concerto
 * @class Core
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Core.
 */

namespace Concerto;

class Core extends Singleton {
    
    /**
     * @todo istanza singleton
     * @access public
     * @var object
     */
    protected static $singleton ;
    
    /**
     * @todo Costruttore della classe, invia la logica al costruttore padre
     *
     */
    protected function costruttore(){
        $this->boot();
    } // fine costruttore()
    
    /**
     * @todo Metodo che avvia il sistema
     * @access public
     */
    public function boot() {
//        // faccio partire Config
//        Config::run();
//        
//        // faccio partire il gestore delle eccezioni
//        Eccezione::run();
//        
//        // faccio partire il getore della sessione
//        Sessione::run();
//        
//        // faccio partire la localizzaione
//        Locale::run();
//        
//        // faccio partire il gestore delle richieste
//        Request::run();
//        
//        // faccio partire security
//        Security::run();
//        
//        // faccio partire il routing
//        Routing::run();
//        
//        // faccio partire le applicazioni
//        App::run();
//        
//        // setto il tipo di Response
//        Response::run();
//        
//        // faccio partire il sistema di templating
//        Template::run();
        
    }
    
}