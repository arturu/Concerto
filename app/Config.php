<?php

/**
 * Classe Config
 *
 * @version	0.1
 * @package	Core
 * @file 	file di configurazione
 * @todo 	modificare soltanto il contenuto tra le apici seguendo le istruzioni
 *                  modifiche non corrette causeranno il blocco del programma
 */
class Config {

    /**
     * @todo singleton pattern
     * @access public
     * @var object
     */
    public static $singleton;

    /**
     * @todo array con le impostazioni
     * @access public
     * @var array
     */
    public $impostazioni = array();

    /**
     * @todo Costruttore della classe
     *
     */
    private function __construct() {
        $this->default_setting();
    }

    /**
     * @todo Metodo che setta le impostazioni di default
     * @access private
     */
    private function default_setting() {
        $this->impostazioni = array();
    }

    /**
     * @todo Metodo che avvia il singleton in caso è avviato ritorna quello già avviato
     * @access public static
     */
    public static function get() {
        // in caso non è settato il $singleton lo costruisco
        if (!isset(self::$singleton))
            self::$singleton = new Config;

        //restituisco in ogni caso l'oggetto
        return self::$singleton;
    }

    /**
     * @todo Metodo che permette di cambiare alcune opzioni durante l'esecuzione,
     * 		 funziona anche da Hook, consigliato se si devono aggiungere parametri
     * @access public
     */
    public function set($set) {
        $this->impostazioni = array_replace_recursive($this->impostazioni, $set);
    }

    /**
     * @todo Metodo che restituisce le impostazioni
     * @access public
     */
    public function config() {
        return $this->impostazioni;
    }

    /**
     * @todo Metodo che reimposta i settaggi, utile durante l'esecuzione per reimpostare i settaggi
     * @access public
     */
    public function reset() {
        $this->default_setting();
    }

    /**
     * @todo Hook che permette alle altre applicazioni di aggiungere nuovi parametri
     * @access public
     */
    public function add($array) {
        $this->impostazioni[] = $array;
    }

}
