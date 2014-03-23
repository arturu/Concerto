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
     * L'applicazione eseguita
     * 
     * @var object
     */
    private $app;
    
    /**
     * @todo Costruttore della classe, invia la logica al costruttore padre
     *
     */
    protected function costruttore(){
        $this->set();
        
        $this->avvia_app();
    } // fine costruttore()
    
    /**
     * @todo Avvia l'applicazione richiesta
     */
    private function avvia_app(){
        // recupero le impostazioni
        $impostazioni = Config::run()->mie();

        // costruisco il nome dell'applicazione da caricare, nel seguente formato
        // \namespace\App
        $nome_app = '\\'.$impostazioni['app'].'\App';

        // controllo se esiste l'App richiesta
        if ( class_exists($nome_app) )
             // istanzio l'applicazione e gli passo il controllo
            $this->app = new $nome_app($impostazioni['parametri']);

        // altrimenti gestisco l'errore
        else    
            throw new Eccezione('Errore non riesco a trovare: '.$nome_app);
    
    }
    
    /**
     * @todo inserisce nel Config i settaggi della classe
     */
    private function set(){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // tramuto in array l'url interno
        $parametri = explode( '/' , $impostazioni['Concerto\Routing']['url_interno']);
        
        // istanzio un oggetto yaml parser
        $yaml = new Parser(); 
        
        // il percorso del file app_pubbliche.yml
        $file = $impostazioni['Concerto\Core']['path_app'] . DIRECTORY_SEPARATOR . 
                'Concerto' . DIRECTORY_SEPARATOR . 
                'config'. DIRECTORY_SEPARATOR . 
                'app_pubbliche.yml';

        // leggo il file app_pubbliche.yml
        $app_pubbliche = $yaml->parse(file_get_contents($file));

        // controllo se l'app da avviare tramite url esiste ed è nelle app_pubbliche,
        // se è in elenco la imposto altrimenti imposto l'app di default
        if ( isset($parametri[1]) && in_array( $parametri[1], $app_pubbliche['app_pubbliche'] ) ) {
            // setto il nome dell'app
            $app = $parametri[1];
            
            // tolgo i prametri inutili
            unset($parametri[0]); // è vuoto
            unset($parametri[1]); // è il nome dell'app
        
            // e resetto l'indice dell'array
            $parametri_app = array_values($parametri);
        }
        else {
            // setto il nome dell'app con l'app di default
            $app = $app_pubbliche['default_app'];
            
            // gli passo tutti i parametri
            $parametri_app = $parametri;
        }
  
        // salvo nel config
        Config::run()->set(array(
            'app'       =>$app,
            'parametri' =>$parametri_app,
            'path_app'  =>$impostazioni['Concerto\Core']['path_app'].DIRECTORY_SEPARATOR.$app,
        ));
    }
    
}