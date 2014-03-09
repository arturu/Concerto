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
        $this->set();
        
        $this->boot();
    } // fine costruttore()
    
    /**
     * @todo Metodo che avvia il sistema
     * @access public
     */
    public function boot() {
       
//        // faccio partire security
//        Security::run();
       
        // faccio partire il getore della sessione
        Sessione::run();
       
        // faccio partire la localizzazione
        Locale::run();
       
        // faccio partire il gestore delle richieste
        Request::run();
       
        // faccio partire il routing
        Routing::run();
       
        // faccio partire le applicazioni
        App::run();
        
//        // setto il tipo di Response
//        Response::run();
       
//        // faccio partire il sistema di templating
//        Template::run();
        
    }
    
    /**
     * @todo inserisce nel Config i settaggi della classe
     */
    private function set(){
        
        // recupero il nome della pagina php usata come front controller
        $front_controller = explode('/' , $_SERVER['SCRIPT_NAME']);
        
        // prendo l'ultimo elemento di $_SERVER['SCRIPT_NAME']
        $front_controller = end( $front_controller );
        
        // recupero la cartella dove è installato il framework a partire dalla web root
        // tolgo il /front controller da $_SERVER['SCRIPT_NAME']
        $cartella_installazione = str_replace('/'.$front_controller, '', $_SERVER['SCRIPT_NAME']);
        
        Config::run()->set(
            array(
                'web_server'             => $this->web_server(),
                'path_base'              => $_SERVER['DOCUMENT_ROOT'].$cartella_installazione,
                'cartella_installazione' => $cartella_installazione,
                'front_controller'       => '/'.$front_controller,
            )
        );
    }
    
    /**
     * @todo Metodo che restituisce informazioni sul webserver, funziona solo con
     *       apache, da estendere anche agli altri webserver
     * 
     * @return array
     */
    private function web_server(){
        
        // in caso è apache
        if( preg_match('/^apache/i', $_SERVER['SERVER_SOFTWARE']) ){
            
            $server = preg_split('/^apache\//i', $_SERVER['SERVER_SOFTWARE']);
            
            return array(
                'nome'                  => 'apache',
                'versione'              => $server[1],
                'admin'                 => $_SERVER['SERVER_ADMIN'],
                'interfaccia_gateway'   => $_SERVER['GATEWAY_INTERFACE'],
                'protocollo'            => $_SERVER['SERVER_PROTOCOL'],
                'moduli'                => apache_get_modules(),
            );
        }
        else 
            return array ( 
                'nome'                  => $_SERVER['SERVER_SOFTWARE'],
                'versione'              => '',
                'admin'                 => $_SERVER['SERVER_ADMIN'],
                'interfaccia_gateway'   => $_SERVER['GATEWAY_INTERFACE'],
                'protocollo'            => $_SERVER['SERVER_PROTOCOL'],
                'moduli'                => '',
                
            );
    }
    
}