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

use Symfony\Component\Yaml\Parser;

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
        
        // istanzio un oggetto yaml parser
        $yaml = new Parser(); 
        
        // il percorso del file app_pubbliche.yml
        $file = $impostazioni['Concerto\Core']['path_app'] . DIRECTORY_SEPARATOR . 'Concerto' . DIRECTORY_SEPARATOR . 'app_pubbliche.yml';

        // leggo il file app_pubbliche.yml
        $app_pubbliche = $yaml->parse(file_get_contents($file));

        // controllo se l'app da avviare tramite url è nelle app_pubbliche
        if (in_array( $parametri[1], $app_pubbliche ) ){
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
        
        // altrimenti gestico l'errore, meglio non segnalare che non esiste l'app
        else
            throw new Eccezione('Errore non riesco a trovare: '.$parametri[1]);
    
    }
    
}