<?php
namespace Test;

use Concerto\Response;

class App {
    
    public function __construct($parametri) {
        $this->default_action($parametri);
    }
    
    public function default_action($parametri) {
        
        Response::run()->set_response('<div>
            <h1>Hello, World!</h1>
            <a href="'.\Concerto\Utility::costruisci_url_assoluto_app('/1/2/3','English US').'">English US Link</a><br />'.
            '<a href="'.\Concerto\Utility::costruisci_url_assoluto('/articolo-del-cms.html','Italiano').'">Articolo in Italiano</a><br />'.  
            '<a href="'.\Concerto\Utility::costruisci_url_assoluto_app('/Controller/View/1.xml','Italiano').'">XML Page</a><br />'.  
            '<a href="'.\Concerto\Utility::costruisci_url_assoluto_app('/Controller/View/1.json','Italiano').'">JSON Page</a><br />'.
            '</div>'
        );
        
    }
}
