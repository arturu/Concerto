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

// per poter utilizzare il parser YAML
// si potrebbe anche evitare questa riga, ma alla riga 49 bisogna instanziare il parser con
// $yaml = new \Symfony\Component\Yaml\Parser();
// che non è molto bello
use Symfony\Component\Yaml\Parser;

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

        // se non dichiarato il namespace all'inizio bisogna instanziare con
        // $yaml = new \Symfony\Component\Yaml\Parser();
        $yaml = new Parser(); 
        
        $impostazioni = Config::run()->mie();
        
        $file = $impostazioni['path_app'] . DIRECTORY_SEPARATOR . 'Concerto' . DIRECTORY_SEPARATOR . 'boot.yml';

        $core_boot = $yaml->parse(file_get_contents($file));

        // avvio le applicazioni come da elenco
        foreach ($core_boot as $value) {
            $value::run();
        }
        
    }
    
    /**
     * @todo inserisce nel Config i settaggi della classe
     */
    private function set(){
        
        // recupero il nome della pagina php usata come front controller
        $front_controller = explode( DIRECTORY_SEPARATOR , $_SERVER['SCRIPT_NAME'] );
        
        // prendo l'ultimo elemento di $_SERVER['SCRIPT_NAME']
        $front_controller = end( $front_controller );
        
        // recupero la cartella dove è installato il framework a partire dalla web root
        // tolgo il /front controller da $_SERVER['SCRIPT_NAME']
        $cartella_installazione = str_replace( DIRECTORY_SEPARATOR.$front_controller, '', $_SERVER['SCRIPT_NAME'] );
        
        Config::run()->set(
            array(
                'web_server'             => $this->web_server(),
                'path_base'              => $_SERVER['DOCUMENT_ROOT'].$cartella_installazione,
                'path_app'               => $_SERVER['DOCUMENT_ROOT'].$cartella_installazione.DIRECTORY_SEPARATOR.'app',
                'path_public'            => $_SERVER['DOCUMENT_ROOT'].$cartella_installazione.DIRECTORY_SEPARATOR.'public',
                'path_temp'              => $_SERVER['DOCUMENT_ROOT'].$cartella_installazione.DIRECTORY_SEPARATOR.'temp',
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