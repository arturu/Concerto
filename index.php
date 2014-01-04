<?php
/**
 * @version     0.1
 * @package	Core
 * @copyright	Copyright (C) 2003 - 2013 Open Source. All rights reserved.
 * @license	GNU/GPL version 3
 * @author 	Pietro Arturo Panetta arturopanetta@gmail.com
 * @todo	Concerto è un software modulare per lo sviluppo di applicazioni web.
 */

    // Path assoluta senza DIRECTORY_SEPARATOR finale
    define( 'PATH_BASE' , dirname(__FILE__) );
    
    // carico l'Autoload delle classi
    require_once( PATH_BASE . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Autoloader.php' );