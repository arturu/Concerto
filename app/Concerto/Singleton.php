<?php
/**
 * Abstract Singleton
 * 
 * @version 0.1
 * @package Concerto
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @credits https://github.com/MichaelFenwick/PHPSingleton/blob/master/Singleton.php
 * @todo Permette di riutilizzare il Pattern Singleton.
 */

namespace Concerto;

abstract class Singleton {
    
    /** 
     * @var Singleton - L'attributo dove verrà salvata l'istanza. 
     *      Singleton::$singleton in questa posizione non salva niente, e stata
     *      inserita per ricordare che le classi che implementano Singleton devonono 
     *      avere il proprio attibuto static $singleton.
     */
    protected static $singleton;

    /**
     * @todo Secondo il Pattern Singleton il costruttore non deve essere accessibile, quindi,
     * gli elementi figli non lo possono fare l'override (si utilizza "final"), ma
     * dobbiamo comunque permettere agli elementi figli di poter utilizzare il costruttore
     * utilizziamo costruttore
     */
    protected final function __construct() {
        $this->costruttore();
    }

    /**
     * @todo Un metodo vuoto che implementeranno di figli con la logica da passare al costruttore.
     */
    abstract protected function costruttore();

    /**
     * @todo Se il singleton non è stato costruito verrà richiamato il metodo costruttore() del figlio e verrà costruito il Sigleton.
     *          Se il singleton esiste verra restituito il singleton
     * @return static L'istanza.
     */
    final public static function run() {
        
        // controllo se esiste il singleton 
        if (!isset(static::$singleton)) {
            
            // recupero la classe chiamata dal figlio
            $classe = get_called_class();
            
            // istanzio la nuova classe
            $nuovo_singleton = new $classe();
            
            // prima di salvare controllo che il figlio abbia dichiarato statico l'attributo singleton
            if (!isset(static::$singleton)) {
                static::$singleton = $nuovo_singleton;
            }
        }
        return static::$singleton;
    }
    
    /**
     * @todo Dato che il singleton deve essere univoco evitiamo che possa essere clonato.
     */
    final public function __clone() {
        throw new Eccezione('Non puoi clonare '. get_called_class() .' esso &egrave; un singleton');
    }

    /**
     * @todo unserialize utilizza __wakeup() e quindi questa operazione può creare una copia,
     *       disattiviamo anche questa possibilità perché violerebbe l'utilità del singleton
     */
    final public function __wakeup() {
        throw new Eccezione('Non puoi fare unserialize di '. get_called_class() .' esso &egrave; un singleton');
    }
}
