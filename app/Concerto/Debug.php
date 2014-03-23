<?php
/**
 * Gestisce il Debug
 * 
 * @version 0.1
 * @package Concerto
 * @class Debug
 * @copyright Copyright (C) 2003 - 2014 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo Gestisce l'avvio delle applicazioni
 */
namespace Concerto;

class Debug extends Singleton {
    
    /**
     * @todo istanza singleton
     * @access public
     * @var object
     */
    protected static $singleton ;
    
    /**
     * Un buffer per memorizzare gli elementi da debuggare
     * 
     * @var array
     */
    protected $buffer;
    
    /**
     * @todo Costruttore della classe, invia la logica al costruttore padre
     *
     */
    protected function costruttore(){

    } // fine costruttore()
    
    /**
     * @todo aggiunge un elemento al buffer
     * 
     * @param var $item ciò che bisogna debuggare
     * @param var $id un identificatore
     */
    public function aggiungi($item,$id){
        $this->buffer[][$id] = $item;
    }
    
    /**
     * @todo inserisce nel Config i settaggi della classe
     */
    public function render(){
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        ini_set('xdebug.var_display_max_depth', 15);
        ini_set('xdebug.var_display_max_children', 256);
        ini_set('xdebug.var_display_max_data', 1024);
        
        $this->buffer['Configurazione di sistema'] = $impostazioni;
        $this->buffer['Stack'] = debug_backtrace();
        
        if ( $impostazioni['Concerto\Response']['formato'] == '\Render\html' )
            var_dump($this->buffer);
    }
    
}