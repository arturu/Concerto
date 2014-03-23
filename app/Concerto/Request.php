<?php
/**
 * Gestore delle richieste
 * 
 * @version 0.1
 * @package Concerto
 * @class Request
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Core.
 */

namespace Concerto;

class Request extends Singleton {
    
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
    } // fine costruttore()
    
    /**
     * @todo inserisce nel Config i settaggi della classe
     */
    private function set(){
        
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // recupero la cartella di installazione
        $cartella_installazione = $impostazioni['Concerto\Core']['cartella_installazione'];
        
        // recupero la request uri senza la cartella d'installazione e query string
        $query_uri = str_replace($cartella_installazione, '', $_SERVER['REQUEST_URI']);
        $richiesta = str_replace('?'.$_SERVER['QUERY_STRING'], '', $query_uri);
        
        // recupero il redirect
        $redirect = str_replace($cartella_installazione, '', isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'');
        
        Config::run()->set(
            array(
                'metodo'              => $_SERVER['REQUEST_METHOD'],
                'get'                 => $_GET,
                'post'                => $_POST,
                'stato_richiesta'     => isset($_SERVER['REDIRECT_STATUS'])?$_SERVER['REDIRECT_STATUS']:false,
                'richiesta'           => $richiesta,
                'parametri_get'       => $_SERVER['QUERY_STRING'],
                'redirect_url'        => $redirect,
                'parametri_redirect'  => isset($_SERVER['REDIRECT_QUERY_STRING'])?$_SERVER['REDIRECT_QUERY_STRING']:false,
            )
        );
    }
}