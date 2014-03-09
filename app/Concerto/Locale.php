<?php
/**
 * Il gestore dell'internazionalizzazione
 * 
 * @version 0.1
 * @package Concerto
 * @class Locale
 * @copyright Copyright (C) 2003 - 2014 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Gestisce l'internazionalizzazione
 */
namespace Concerto;

class Locale extends Singleton {
    
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
        //Recupero la sessione
        $sessione = Sessione::run()->mie();
        
        // se non settata nella sessione recupero la lingua preferita del browser
        if (!$sessione) {
            $browser = $this->browser();

            Sessione::run()->set(
                array(
                    'lingua'    => $browser['lingua'],
                    'locale'    => $browser['locale']
                )
            );
        }
        
    }
    
    /**
     * @todo stabilisce la localizzazione del browser
     */
    private function browser() {
        // recupero la parte di dati che mi interessa
        $dati = explode( ',' , $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
        
        // recupero la localizzazione della prima lingua
        $locale = $dati[0];
        
        // recupero la sigla della lingua
        $lingua = explode(';',$dati[1]);
        
        return array('lingua'=>$lingua[0],'locale'=>$locale);
    }
    
    /**
     * @todo metodo che permette di cambiare la lingua e la localizzazione
     * 
     * @param array $set Un array con le impostazioni da aggiornare: array('lingua'=>'xx','locale'=>'xx-XX')
     */
    public function cambio($set){
        Sessione::run()->set($set);
    }
    
}