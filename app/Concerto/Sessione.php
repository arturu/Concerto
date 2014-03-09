<?php
/**
 * Il gestore della sessione
 * 
 * @version 0.1
 * @package Concerto
 * @class Sessione
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Gestisce la Sessione
 */
namespace Concerto;

class Sessione extends Singleton {
    
    /**
     * @todo istanza singleton
     * @access public
     * @var object
     */
    protected static $singleton ;
    
    /**
     * @todo un attributo dove viene salvata la sessione
     * @access private
     * @var array
     */
    private $sessione;
    
    /**
     * @todo Costruttore della classe, invia la logica al costruttore padre
     *
     */
    protected function costruttore(){
        // faccio partire la sessione PHP
        session_start();
        
        // mi salvo la sessione nel config
        Config::run()->set( $_SESSION );
        
        // cancello la sessione in modo che le altre applicazioni utilizzino obbligatoriamente Concerto\Sessione
        $_SESSION = "Questo elemento e' utilizzabile soltanto attraverso il gestore della sessione: Concerto\Sessione";
        
        $this->default_settings();   
    } // fine costruttore()
    
    /**
     * @todo inserisce nel Config i settaggi della classe
     */
    private function default_settings(){
        Config::run()->set(
            array(
                'sessione_id'    => isset($_COOKIE['PHPSESSID'])?$_COOKIE['PHPSESSID']:false,
                
                // temporaneamente finché non sarà sviluppata l'app gestione utenti
                'utente' => array(
                    'autenticato'   => true,
                    'roulo'         => 'admin',
                    'id'            => 1,
                )
            )
        );
    }
    
    /**
     * @todo Metodo che restituisce le sessioni
     * @access public
     * @return array Un array con le sessioni
     */
    public function get() {
        return Config::run()->mie();
    }
    
    
    /**
     * @todo Metodo che restituisce solo la sessione specifica dell'app
     * @access public
     * @return array Un array con le sessioni specifiche
     */
    public function mie() {
        // recupero la pila delle classi che hanno richiesto questa operazione
        $pila = debug_backtrace();
        
        // prendo la classe dal penultimo elemento
        $classe_richiedente = $pila[1]['class'];
        
        // recupero tutte le sessioni
        $sessioni = Config::run()->mie();
        
        // return in base se esiste o meno il record
        $return = ( isset($sessioni[$classe_richiedente]) ) ? $sessioni[$classe_richiedente] : false ;
        
        return $return;
    }
    
    /**
     * @todo Metodo che permette di cambiare alcune opzioni nella sessione
     * @access public
     * @param array $set l'array con le impostazioni da modificare
     */
    public function set($set) {
        // recupero la pila delle classi che hanno richiesto questa operazione
        $pila = debug_backtrace();
        
        // prendo la classe dal penultimo elemento
        $classe_richiedente = $pila[1]['class'];
        
        // aggiornamento/inserimento relativo all'app che ha richiesto l'operazione
        $sessioni = array_replace_recursive( Config::run()->mie(), array($classe_richiedente=>$set) );
        
        Config::run()->set( $sessioni );
    }
    
    /**
     * @todo Il distruttore della classe, ricopio nella sessione
     */
    function __destruct() {
        $_SESSION = Config::run()->mie();
    }
    
}