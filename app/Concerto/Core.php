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

class Core {
    
    /**
     * @todo singleton pattern
     * @access public
     * @var object
     */
    public static $singleton ;
    
    /**
     * @todo Costruttore della classe
     *
     */
    private function __construct(){
        $this->boot();
    } // fine __construct
    
    /**
     * @todo Metodo che avvia il singleton in caso è avviato ritorna quello già avviato
     * @access public static
     */
    public static function run() {
        // in caso non è settato il $singleton lo costruisco
        if (!isset(self::$singleton)){
            $classe = __CLASS__ ;
            self::$singleton = new $classe;
        }

        //restituisco in ogni caso l'oggetto
        return self::$singleton;
    }
    
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