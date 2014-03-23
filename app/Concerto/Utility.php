<?php
/**
 * Fornisce delle scorciatoie ad attività maggiormente usate
 * 
 * @version 0.1
 * @package Concerto
 * @class Utility
 * @copyright Copyright (C) 2003 - 2014 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Utility
 */
namespace Concerto;

class Utility {
    
    /**
     * @todo Questo metodo genera un url assoluto, utile per collegare più pagine
     *       tra di loro. N.B. da utilizzare solo per collegare pagine e non file
     *       statici, per quest'ultimo caso esiste un apposito metodo.
     * 
     * @param string $query_string
     * @param string $lingua la lingua
     * @return string
     */
    public static function costruisci_url_assoluto($query_string,$lingua=false) {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // aggiungo eventualmente la lingua
        $query_string = \Concerto\Routing::run()->aggiungi_lingua_url($query_string,$lingua);
        
        // costruisco l'url assoluto, ['base_url'] arriva già impostato in base al rewriting
        $url = $impostazioni['Concerto\Routing']['base_url'] . $query_string;
        
        return $url;
    }
    
    public static function costruisci_url_assoluto_app($query_string,$lingua=false){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        return self::costruisci_url_assoluto( '/'.$impostazioni['Concerto\App']['app'].$query_string, $lingua);
    }
    
}