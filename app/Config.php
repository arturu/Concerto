<?php

/**
 * Classe Config
 *
 * @version	0.1
 * @package	Core
 * @class 	Config
 * @todo 	modificare soltanto il contenuto tra le apici seguendo le istruzioni nel metodo default_setting()
 */
class Config {

    /**
     * @todo singleton pattern
     * @access private
     * @var object
     */
    private static $singleton;

    /**
     * @todo array con le impostazioni
     * @access private
     * @var array
     */
    private $impostazioni = array();

    /**
     * @todo Costruttore della classe
     *
     */
    private function __construct() {
        $this->default_setting();
    }

    /**
     * @todo Metodo che avvia il singleton in caso è avviato ritorna quello già avviato
     * @access public static
     */
    public static function run() {
        // in caso non è settato il $singleton lo costruisco
        if (!isset(self::$singleton)){
            $classe = __CLASS__ ;
            self::$singleton = new $classe;
        }

        //restituisco in ogni caso l'oggetto
        return self::$singleton;
    }

    /**
     * @todo Metodo che setta le impostazioni di default
     * @access private
     */
    private function default_setting() {
        $this->impostazioni = array(
            /* esempio */
            'chiave'=>'valore',
        );
    }
    
    /**
     * @todo Metodo che restituisce le impostazioni
     * @access public
     * @return array Un array con le impostazioni
     */
    public function get() {
        return $this->impostazioni;
    }

    /**
     * @todo Metodo che permette di cambiare alcune opzioni durante l'esecuzione
     * @access public
     * @param array $set l'array con le impostazioni da modificare
     */
    public function set($set) {
        $this->impostazioni = array_replace_recursive($this->impostazioni, $set);
    }

    /**
     * @todo Metodo che reimposta i settaggi, utile durante l'esecuzione per reimpostare i settaggi
     * @access public
     */
    public function reset() {
        $this->default_setting();
    }

    /**
     * @todo Metodo che permette alle altre applicazioni di aggiungere nuovi parametri
     * @access public
     * @param string $chiave Una chiave per identificare la radice delle impostazioni aggiunte
     */
    public function add($chiave,$array) {
        $this->impostazioni[$chiave] = $array;
    }

}
