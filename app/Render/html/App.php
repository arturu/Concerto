<?php
/**
 * Gestisce il render in HTML
 * 
 * @version 0.1
 * @package Render
 * @class App
 * @copyright Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license GNU/GPL version 3
 * @author Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo HTML.
 */

namespace Render\html;

class App extends \Render\xml\App {
    
    public function __construct($render) {
        echo $render;
    }
    
}