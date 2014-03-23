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

use Symfony\Component\Yaml\Parser;

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
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // istanzio un oggetto yaml parser
        $yaml = new Parser(); 
        
        // il percorso del file locale.yml contenente le lingue attive
        $file = $impostazioni['Concerto\Core']['path_app'] . DIRECTORY_SEPARATOR . 
                'Concerto' . DIRECTORY_SEPARATOR . 
                'config'. DIRECTORY_SEPARATOR . 
                'locale.yml';

        // leggo il file app_pubbliche.yml
        $lingue = $yaml->parse(file_get_contents($file));
        
        // salvo le lingue nella configurazione di sistema
        Config::run()->set(array('lingue'=>$lingue));
        
        //Recupero la sessione
        $sessione = Sessione::run()->mie();
        
        // se non settata nella sessione recupero la lingua preferita del browser
        if (!$sessione)
            Sessione::run()->set( $this->browser() );
            
    }
    
    /**
     * @todo stabilisce la localizzazione del browser
     * 
     * @return array
     */
    private function browser() {
        // recupero la parte di dati che mi interessa
        $dati = explode( ',' , $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
        
        // recupero la localizzazione della prima lingua
        $locale_browser = $dati[0];
        
        // recupero la sigla della lingua
        $lingua_browser = explode(';',$dati[1]);
        
        // restituisco la lingua
        return $this->verifica_lingua( array('lingua'=>$lingua_browser[0],'locale'=>$locale_browser) );
    }
    
    /**
     * @todo metodo che permette di cambiare la lingua e la localizzazione
     * 
     * @param array $lingua Un array con le impostazioni da aggiornare: array('lingua'=>'xx','locale'=>'xx-XX')
     */
    public function cambio($lingua){
        Sessione::run()->set( $this->verifica_lingua($lingua) );
    }
    
    /**
     * Metodo che data una lingua verifica se è attiva e in caso la imposta,
     * altrimenti imposta la lingua di default
     * 
     * @param array $lingua Un array con le impostazioni da aggiornare: array('lingua'=>'xx','locale'=>'xx-XX')
     * @return array array('lingua'=>'xx','locale'=>'xx-XX','label'=>'xxxxxxx')
     */
    public function verifica_lingua ($lingua){
        // recupero le impostazioni
        $lingue_attive = Config::run()->mie();

        // ciclo tutte le lingue attive
        foreach ($lingue_attive['lingue']['lingue_attive'] as $key => $value) {
            // se la lingua del browser è tra le lingue attive la imposto
            if ( $value['lingua'] == $lingua['lingua'] && $value['locale'] == $value['locale'] ){
                $lingua = array( 
                    'lingua' => $value['lingua'],
                    'locale' => $value['locale'],
                    'label' => $key
                );
            }
        }
        
        // se non è impostata la lingua la imposto
        if ( !isset($lingua) )
            $lingua = array( 
                'lingua' => $lingue_attive['lingue']['default']['lingua'],
                'locale' => $lingue_attive['lingue']['default']['locale'],
                'label' => $lingue_attive['lingue']['default']['label']
            );
        
        return $lingua;
    }
    
}