<?php
/**
 * Gestisce l'avvio delle applicazioni
 * 
 * @version 0.1
 * @package Concerto
 * @class Sessione
 * @copyright Copyright (C) 2003 - 2014 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Gestisce l'avvio delle applicazioni
 */
namespace Concerto;

class App extends Singleton {
    
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
        $this->avvia_app();
    } // fine costruttore()
    
    /**
     * @todo Avvia l'applicazione richiesta
     */
    private function avvia_app(){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // tramuto in array l'url interno
        $parametri = explode( '/' , $impostazioni['Concerto\Routing']['url_interno']);
        
        // costruisco il nome dell'applicazione da caricare, nel seguente formato
        // \namespace\App
        $app = "\\".$parametri[1].'\App';

        // controllo se esiste l'App richiesta
        if ( class_exists($app) )
             // istanzio l'applicazione e gli passo il controllo
            $app = new $app($parametri);
        
        // altrimenti gestisco l'errore
        else    
            throw new Eccezione('Errore non riesco a trovare: '.$app);
        
    }
    
}