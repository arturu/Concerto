<?php
/**
 * Il router principale dell'applicazione
 * 
 * @version 0.1
 * @package Concerto
 * @class Routing
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Gestisce il Routing
 */

namespace Concerto;

class Routing extends Singleton {
        
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
        // stabilisco su quale protocollo è richiesta la pagina
        $https = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ) ? true : false ;
        $protocollo = ( $https ) ? 'https://' : 'http://' ;
        
        //stabilisco se il rewrite è attivo 
        $rewrite = $this->rewrite();
        
        // setto l'url base 
        $base_url = $this->base_url($protocollo, $https, $rewrite);

        // setto l'url interno 
        $query_string = $this->query_string();
        
        // setto il config
        Config::run()->set(
            array(
                'protocollo'    => $protocollo,
                'dominio'       => $_SERVER['SERVER_NAME'],
                'porta'         => $_SERVER['SERVER_PORT'],
                'rewrite'       => $rewrite,
                'base_url'      => $base_url,
                'url_interno'   => $query_string,
            )
        );
    }
    
    /**
     * @todo Metodo che stabilisce se il rewrite è attivo o meno, solo su apache
     *       da estendere in futuro anche agli altri server
     * 
     * @return bool
     */
    private function rewrite() {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // controllo se è attivo mod_rewrite di apache
        $mod_rewrite = in_array('mod_rewrite', $impostazioni['Concerto\Core']['web_server']['moduli']);
        
        // percorso file .htaccess
        $htaccess_file = $impostazioni['Concerto\Core']['path_base'] . '/.htaccess';
        
        // controllo se .htaccess esiste ed è leggibile
        $htaccess = ( file_exists($htaccess_file) && is_readable($htaccess_file) ) ? true : false ;
        
        // stabilisco se il rewrite è attivo
        return ( $mod_rewrite && $htaccess) ? true : false ;
    }
    
    /**
     * @todo Metodo che restituisce l'url base, considera se l'installazione si trova
     *       in una sottocartella, oppure se il rewriting è attivo o meno.
     * 
     * @param string $protocollo
     * @param bool $https
     * @param bool $rewrite
     * @return string
     */
    private function base_url($protocollo,$https,$rewrite) {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        $base_url = $protocollo;
        $base_url .= $_SERVER['SERVER_NAME'];
        
        // se su https
        if ($https)
            // se è la porta di default non inserisco la porta
            $base_url .= ( $_SERVER['SERVER_PORT']=='443'  )? '' : ':'.$_SERVER['SERVER_PORT'];
        
        // altrimenti è su http
        else
            // se è la porta di default non inserisco la porta
            $base_url .= ( $_SERVER['SERVER_PORT']=='80'  )? '' : ':'.$_SERVER['SERVER_PORT'];
        
        // aggiungo la cartella di installazione del software
        $base_url .= $impostazioni['Concerto\Core']['cartella_installazione'];
        
        // se non è attivo il rewriting e se non si è nella home, aggiungo il front controller
        if ( !$rewrite )
            // se il front controller è '/index.php' non lo inserisco
            $base_url .= ($impostazioni['Concerto\Core']['front_controller'] == '/index.php') 
                ? '/?q='
                : $impostazioni['Concerto\Core']['front_controller'] . '?q=' ;
        
        // restituisco l'url base
        return $base_url;
    }
    
    /**
     * @todo Metodo che setta una query string standardizzata e pulita dalla 
     *       cartella di installazione, dal front controller e da altri caratteri.
     * 
     * @return string
     */
    private function query_string() {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();

        // tolgo l'eventuale parametro
        $parametri_get = str_replace('q=' , '' , $impostazioni['Concerto\Request']['parametri_get']);
        // standardizzo i parametri get in modo da avere un solo formato url con rewrite attivo e non
        $parametri_get = str_replace('&' , '/' , $parametri_get);
        // tolgo l'eventuale '?' dalla richiesta
        $parametri_get = str_replace('?' , '/' , $parametri_get);
        
        // setto in maniera diversa se arriva come parametro "q=" o come rewrite
        // dato che ho standardizzato la query string essa sarà identica con o senza mod rewrite attivo
        $query_string = ( isset($impostazioni['Concerto\Request']['get']['q']) )
            ? $parametri_get // in caso viene passato un url tramite parametro
            : $impostazioni['Concerto\Request']['richiesta'] . '/'. $parametri_get;
        
        // controllo se sono nella home
        if ( ($impostazioni['Concerto\Request']['richiesta']=='/index.php' || $impostazioni['Concerto\Request']['richiesta']=='/' )   
            && 
            (empty($impostazioni['Concerto\Request']['parametri_get'])) 
           )
           $query_string = '/';
        
        // controllo se deve essere presente o meno la lingua nell'url
        $query_string = $this->lingua($query_string);
        
        return $query_string;
    }
    
    /**
     * @todo Metodo che gestisce la presenza della lingua nell'url. Se '$aggiungi'
     *       è uguale a 'false' e se la lingua è presente nella query string essa 
     *       viene cancellata, altrimenti, viene aggiunta. 
     * 
     * @param string $query_string
     * @param bool $aggiungi
     * @return string
     */
    private function lingua($query_string,$aggiungi=false){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        if ( $impostazioni['Concerto\Config']['url']['lingua_in_url'] ) {
            
            // controllo il formato della lingua, e ne calcolo la lunghezza, "xx", "xx-XX" oppure "xx_XX"
            $lunghezza = (int) strlen($impostazioni['Concerto\Config']['url']['formato_lingua']);

            // dalla lunghezza mi stabilisco in che formato è la lingua nell'url
            $formato = ( $lunghezza==2 ) ? 'lingua' : 'locale';

            // prelevo la prima parte della query_string
            // es substr( '/xx-XX/app/azione/variabile/valore', 0 , 6 ) == '/xx-XX'
            $lingua_in_url = substr($query_string, 0, $lunghezza + 1);

            // mi prelevo la lingua dalla sessione in base al formato
            $lingua_browser = $impostazioni['Concerto\Sessione']['Concerto\Locale'][$formato];

            // se la prima parte che ho prelevato da $query_string corrisponde alla lingua
            // e bisogna cancellarla dalla $query_string
            if ( $lingua_in_url == '/'.$lingua_browser && $aggiungi==false )
                // la rimuovo dalla $query_string restituendo tutto quello che c'è dopo
                // la lunghezza della lingua compreso lo "/" iniziale
                $query_string = substr($query_string, $lunghezza + 1);
            
            // se devo aggiungere la lingua nella $query_string e essa non esiste la aggiungo
            if ( $lingua_in_url != '/'.$lingua_browser && $aggiungi==true )
                $query_string = '/'.$lingua_browser . $query_string;
              
            // se la parte che ho prelevato da $query_string è uguale alla lingua che devo
            // inserire, significa che la $lingua è già nella $query_string, non devo fare nulla
            ;
            
            // si potrebbe pensare che in caso di cambio lingua la precedente condizione risulti
            // verificata, andando a costruire una $query_string simile a:
            // /it-IT/en-EN/controller/action/...
            // ma questo non si verifica, in quanto, quando viene eseguita la prima volta questa funzione:
            // $this->costruttore()->set()->query_string()->lingua();
            // viene tolta, non bisogna fare nulla
            ;
        }
            
        return $query_string;
        
    }
    
    /**
     * @todo Questo metodo genera un url assoluto, utile per collegare più pagine
     *       tra di loro. N.B. da utilizzare solo per collegare pagine e non file
     *       statici, per quest'ultimo caso esiste un apposito metodo.
     * 
     * @param string $query_string
     * @return string
     */
    public function costruisci_url_assoluto($query_string) {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // aggiungo eventualmente la lingua
        $query_string = $this->lingua($query_string,true);
        
        // costruisco l'url assoluto, ['base_url'] arriva già impostato in base al rewriting
        $url = $impostazioni['Concerto\Routing']['base_url'] . $query_string;
        
        return $url;
    }
    
}


