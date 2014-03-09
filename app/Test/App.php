<?php
namespace Test;

class App {
    
    public function __construct($parametri) {
        var_dump(
            'App di Test Avviata',
            $parametri,
            \Concerto\Routing::run()->costruisci_url_assoluto('/Test/controller/action/a/b/c/d=1/e=2/'),
            \Concerto\Config::run()->get()
        );
    }
}
