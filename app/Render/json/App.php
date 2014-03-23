<?php
/**
 * Gestisce il render in json
 * 
 * @version 0.1
 * @package Render
 * @class App
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo json.
 */

namespace Render\json;

class App {
    
    public function __construct($render) {
        header('Content-type: application/json');
        echo json_encode($render);
    }
    
}