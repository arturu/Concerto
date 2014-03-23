<?php
/**
 * Gestisce il render in XML
 * 
 * @version 0.1
 * @package Render
 * @class App
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo XML.
 */

namespace Render\xml;

class App {
    
    public function __construct($render) {
        header ("Content-Type: text/xml");
        echo '<?xml version="1.0" encoding="UTF-8"?>'.$render;
    }
    
}