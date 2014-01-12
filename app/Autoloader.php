<?php
/**
 * Autoloader di classi
 * 
 * @version	0.1
 * @package	Core
 * @file 	Autoloader di classi.
 * 
 * @todo    Dato il nome della classe (Cartella_Classe) cerco i file in vari percosi
 *          sostituendo "_" con DIRECTORY_SEPARATOR.
 *          Potrei usare RecursiveDirectoryIterator ma dato che i possibili percorsi sono 
 *          pochi utilizzo questa soluzione, di gran lunga più veloce che scandire
 *          tutti i file e le sottocartelle in "app"
 */
spl_autoload_register(
    function($classe) { // http://www.php.net/manual/it/functions.anonymous.php

        // la cartella base delle app
        $base = dirname(__FILE__) . DIRECTORY_SEPARATOR ;

        // sostituisco "_" con DIRECTORY_SEPARATOR in modo da caricare classi dentro le cartelle
        $classe = str_replace('_', DIRECTORY_SEPARATOR, $classe);

        // percorso completo del file da includere
        $file = $base . $classe . '.php';
        
        // controllo se esiste ed è leggibile
        if ( file_exists($file) && is_readable($file)) {
            require_once $file;
            return true;
        }
        return false;

    });
    