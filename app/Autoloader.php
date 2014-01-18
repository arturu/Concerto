<?php
/**
 * Autoloader di classi
 * 
 * @version	0.2
 * @package	Autoload
 * @file 	Autoloader di classi.
 * 
 * @todo        Dato il nome della classe (namespace\Classe) carico la relativa classe.
 */
spl_autoload_register(
    function($classe) {

        // la cartella base delle app
        $base = __DIR__ . DIRECTORY_SEPARATOR ;

        // sostituisco "\" con DIRECTORY_SEPARATOR in modo da caricare classi dentro le cartelle
        $classe = str_replace('\\', DIRECTORY_SEPARATOR, $classe);

        // percorso completo del file da includere
        $file = $base . $classe . '.php';
        
        // controllo se esiste ed è leggibile
        if ( file_exists($file) && is_readable($file) ) {
            require_once $file;
            return true;
        }
        
        return false;

    });
    