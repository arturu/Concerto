<?php
/**
 * Classe Config
 *
 * @version 0.1
 * @package Concerto
 * @class Config
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo modificare soltanto il contenuto tra le apici seguendo le istruzioni nel metodo default_setting()
 */

namespace Concerto;

class Config extends Singleton {

    /**
     * @todo istanza singleton
     * @access private
     * @var object
     */
    protected static $singleton;

    /**
     * @todo array con le impostazioni
     * @access private
     * @var array
     */
    private $impostazioni = array();

    /**
     * @todo Costruttore della classe, invia la logica al costruttore padre
     *
     */
    protected function costruttore() {
        $this->default_settings();
    }

    /**
     * @todo Metodo che setta le impostazioni di default
     * @access private
     */
    private function default_settings() {
        $this->set( array(
            'url' => array( 
                'lingua_in_url' => true, // se nell'url deve essere presente la lingua
                'lingua_su_terzo_livello' => false, // se true la lingua è il terzo livello xx.dominio.tld
                'formato_lingua' => 'xx', // impostare se deve essere nel formato "xx", "xx-XX" oppure "xx_XX"
            )
        ));
    }
    
    /**
     * @todo Metodo che restituisce le impostazioni
     * @access public
     * @return array Un array con le impostazioni
     */
    public function get() {
        return $this->impostazioni;
    }
    
    /**
     * @todo Metodo che restituisce solo le impostazioni specifiche dell'app
     * @access public
     * @return array Un array con le impostazioni specifiche
     */
    public function mie() {
        // recupero la pila delle classi che hanno richiesto questa operazione
        $pila = debug_backtrace();
        
        // prendo la classe dal penultimo elemento
        $classe_richiedente = $pila[1]['class'];
        
        // return in base se esiste o meno il record
        $return = (isset($this->impostazioni[$classe_richiedente])) ?$this->impostazioni[$classe_richiedente] : false ;
        
        return $return;
    }

    /**
     * @todo Metodo che permette di cambiare alcune opzioni durante l'esecuzione
     * @access public
     * @param array $set l'array con le impostazioni da modificare
     */
    public function set($set) {
        // recupero la pila delle classi che hanno richiesto questa operazione
        $pila = debug_backtrace();
        
        // prendo la classe dal penultimo elemento
        $classe_richiedente = $pila[1]['class'];

        // aggiornamento/inserimento relativo all'app che ha richiesto l'operazione
        $this->impostazioni = array_replace_recursive($this->impostazioni, array($classe_richiedente=>$set) );
    }

}
