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
        $query_string = $this->rimuovi_lingua_url($query_string);
        
        // in caso c'è la '/' alla fine lo tolgo in quanto inutile
        if ( substr($query_string, -1) == '/' )
             $query_string = substr($query_string, 0, -1);
        
        return $query_string;
    }
    
    /**
     * @todo Metodo che rimuove la lingua dall'url.
     * 
     * @param string $query_string
     * @return string
     */
    private function rimuovi_lingua_url($query_string){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // mi stabilisco in che formato è la lingua nell'url
        $formato = $this->formato_lingua_in_url();

        // stabilisco se e quale è la lingua nella query string
        $lingua = $this->lingua_in_url($query_string);

        // mi prelevo la lingua del browser dalla sessione in base al formato
        $lingua_browser = $impostazioni['Concerto\Sessione']['Concerto\Locale'][$formato['formato']];

        // in caso la lingua è già presente la cancello dalla $query_string
        if ( $lingua ){
            // la rimuovo dalla $query_string restituendo tutto quello che c'è dopo
            // la lunghezza della lingua compreso lo "/" iniziale
            $query_string = substr($query_string, $formato['lunghezza'] + 1);

            // in caso la lingua presente nell'url è diversa da quella del browser
            // chiedo un cambio lingua
            if ( $lingua[$formato['formato']] != $lingua_browser )
                Locale::run()->cambio($lingua);
        }
            
        return $query_string;
        
    }
    
    /**
     * Stabilisce se inserire la lingua nell'url
     * 
     * @param string $query_string
     * @param string $lingua
     * @return string
     */
    public function aggiungi_lingua_url($query_string,$lingua=false) {
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // se la lingua deve essere presente nell'url
        if ( $impostazioni['Concerto\Config']['url']['lingua_in_url'] ) {
            // stabilisco in che formato è la lingua nell'url
            $formato = $this->formato_lingua_in_url();
            
            // prelevo la lingua del browser dalla sessione in base al formato
            $lingua_browser = $impostazioni['Concerto\Sessione']['Concerto\Locale'][$formato['formato']];
        
            // se nella query_string viene inserita accidentalmente la lingua la rimuovo
            $query_string = $this->rimuovi_lingua_url($query_string);
            
            // imposto la lingua inviata come parametro se è tra le lingue attive
            if ( $lingua && isset($impostazioni['Concerto\Locale']['lingue']['lingue_attive'][$lingua]) )
                $query_string = '/'.$impostazioni['Concerto\Locale']['lingue']['lingue_attive'][$lingua][$formato['formato']]. $query_string;
            
            // altrimenti imposto quella del browser
            else 
                // la aggiungo alla query_string
                $query_string = '/'.$lingua_browser . $query_string;
        }
        
        return $query_string;

    }
    
    /**
     * Stabilisce se e quale lingua è nella query_string
     * 
     * @param type $query_string
     * @return boolean || string
     */
    private function lingua_in_url($query_string){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();

        // stabilisco in che formato è la lingua nell'url, lingua o locale
        $formato = $this->formato_lingua_in_url();

        // prelevo la prima parte della query_string a seconda del formato['lunghezza']
        // es substr( '/xx-XX/app/azione/variabile/valore', 0 , 6 ) == '/xx-XX'
        $lingua_in_url = substr($query_string, 0, $formato['lunghezza'] + 1);
        
        // ciclo con tutte le lingue attive per vedere quale è stata passata tramite url
        foreach ($impostazioni['Concerto\Locale']['lingue']['lingue_attive'] as $key => $value) {
            if ( '/'.$value[$formato['formato']] == $lingua_in_url )
                // in caso la trovo la restituisco
                return $value;
        }
        
        // in caso non è stata trovata nessuna lingua restituisco false
        return false;
    }
    
    /**
     * Stabilisce in che formato è la lingua nell'url
     * 
     * @return array('formato'=>xx,'lunghezza'=>0)
     */
    private function formato_lingua_in_url (){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // controllo il formato della lingua, e ne calcolo la lunghezza, "xx", "xx-XX" oppure "xx_XX"
        $lunghezza = (int) strlen($impostazioni['Concerto\Config']['url']['formato_lingua']);

        // dalla lunghezza mi stabilisco in che formato è la lingua nell'url e lo restituisco
        $formato = ( $lunghezza==2 ) ? 'lingua' : 'locale';
        
        return array('formato'=>$formato,'lunghezza'=>$lunghezza);
    }
    
    /**
     * Metodo utilizzato da Concerto\Response per pulire l'url_interno dal tipo di formato 
     * 
     * @param string $estensione
     */
    public function pulisci_tipo_formato($estensione) {
        // recupero le mie impostazioni
        $impostazioni = Config::run()->mie();
        
        $url_interno = str_replace('.'.$estensione, '', $impostazioni['url_interno']);
        
        Config::run()->set( array('url_interno'=>$url_interno) );
    }
    
}
