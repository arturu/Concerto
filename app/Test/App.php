<?php
namespace Test;

use Concerto\Response;
use Concerto\Config;
use Symfony\Component\Yaml\Parser;

class App {
    
    public function __construct($parametri) {
        $this->default_action($parametri);
    }
    
    public function default_action($parametri) {
        
        // recupero le impostazioni
        $impostazioni = Config::run()->get();
        
        // istanzio un oggetto yaml parser
        $yaml = new Parser(); 
        
        // recupero i dati di output
        $dati = $yaml->parse(file_get_contents(
            $impostazioni['Concerto\Core']['path_app'] . DIRECTORY_SEPARATOR . 
            'Test' . DIRECTORY_SEPARATOR .
            'articolo_1.yml'
        ));
        
        Response::run()->set_response($dati);
        Response::run()->set_vista('articolo');
        
    }
}
