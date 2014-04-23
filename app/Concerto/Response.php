<?php
/**
 * Gestore Response
 * 
 * @version 0.1
 * @package Concerto
 * @class Response
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Response.
 */

namespace Concerto;

use Symfony\Component\Yaml\Parser;

class Response extends Singleton {
    
    /**
     * @todo istanza singleton
     * @access public
     * @var object
     */
    protected static $singleton ;
    
    /**
     * @todo vettore dove viene salvato l'output delle App
     * @access private
     * @var mix
     */
    private $response;
    
    /**
     * Imposta la vista da utilizzare
     * 
     * @var string
     */
    public $vista;
    
    /**
     * La visualizzazione Eseguita
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
    } // fine costruttore()
    
    /**
     * @todo inserisce nel Config i settaggi della classe, setta in automatico il tipo di formato
     */
    private function set(){
        
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // cerco splitto per il punto
        $array_estensione = explode('.', $impostazioni['Concerto\Routing']['url_interno']);
        
        // prendo l'ultimo elemento
        $estensione = end($array_estensione);
        
        // istanzio un oggetto yaml parser
        $yaml = new Parser(); 
        
        // il percorso del file response.yml contenente il tipo di response
        $file = $impostazioni['Concerto\Core']['path_app'] . DIRECTORY_SEPARATOR . 
                'Concerto' . DIRECTORY_SEPARATOR . 
                'config'. DIRECTORY_SEPARATOR . 
                'response.yml';

        // leggo il file app_pubbliche.yml
        $response = $yaml->parse(file_get_contents($file));
        
        // se il riconoscimento automatico è impostato su true
        if ( $response['riconoscimento_automatico'] )
            // ciclo i tipi di response e lo imposto
            foreach ($response['tipi_abilitati'] as $value){
                if ( $value ==  $response['default_namespace'].$estensione){
                    // il tipo di formato di response
                    $formato = $response['default_namespace'].$estensione;
                    break;
                }
            }
        
        // se non impostato il tipo di formato response, setto quello di default
        $formato = ( isset($formato) ) ? $formato : $response['default'];
        
        // pulisco url_interno dall'eventuale tipo di formato response
        Routing::run()->pulisci_tipo_formato($estensione);
        
        Config::run()->set( array(
            'formato'       => $formato , 
            'estensione'    => $estensione ,
            'Response'      => $response ,
        ));
    }
    
    /**
     * Imposta il formato del response in caso si decide non usare il 
     * riconoscimento automatico
     * 
     * @param string $formato
     * @param string $default_namespace
     */
    public function set_formato($formato,$default_namespace=false) {
        // recupero le impostazioni
        $impostazioni = Config::run()->mie();
        
        // controllo se viene passato il namespace per caricare un Render di terze parti
        if ($default_namespace)
            $formato = $default_namespace.$formato;
        
        // altrimenti carico quello di default
        else
            $formato = $impostazioni['Response']['default_namespace'].$formato;

        // controllo che il formato sia tra quelli abilitati
        if (in_array($formato, $impostazioni['Response']['tipi_abilitati']) )
            Config::run()->set( array('formato' => $formato ));
        else
            throw new Eccezione('Il formato scelto ('.$formato.') non e\' gestito');
    }

    /**
     * Restituisce il formato del response
     * 
     * @return string
     */
    public function get_formato(){
        // recupero le impostazioni
        $impostazioni = Config::run()->mie();
        
        return $impostazioni['formato'];
    }
    
    /**
     * Le app utilizzano questo metodo per salvare l'output
     * 
     * @param type $reponse - il response da salvare
     * @param string $chiave - un identificatore per poter inserire più response
     */
    public function set_response ($response,$chiave=false){
        if ($chiave)
            $this->response[$chiave] = $response;
        else
            $this->response = $response;
    }
        
    /**
     * Il response usa questo metodo per accedere ai dati da visualizzare
     * 
     * @param type $reponse
     */
    public function get_response (){
        return $this->response;
    }
    
    /**
     * Le app utilizzano questo metodo per salvare la vista da caricare
     * 
     * @param type $reponse
     */
    public function set_vista ($vista){
        $this->vista = $vista;
    }
      
    /**
     * Il response recupera da vista da renderizzare tramite questo metodo
     * 
     * @param type $reponse
     */
    public function get_vista(){
        return $this->vista;
    }
    
    /**
     * Metodo che seleziona il tipo di output
     */
    public function render(){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();

        // nome del tipo di response da avviare nel formato
        // \Render\tipo\App
        $r = $impostazioni['Concerto\Response']['formato'].'\App';
        
        // in base al formato corrente avvio l'app che gestisce il tipo di response
        $this->app = new $r();
        
        // debug output
        if ( $impostazioni['Concerto\Config']['debug_mode'] )
            \Concerto\Debug::run()->render();
    }
    
}