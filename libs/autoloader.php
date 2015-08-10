<?php
/*
 * creates autoload function
 * registers function in autoload stack. 
 */

function autoload($classname) {

    $paths = array(
        'libs/',
        '../libs/'
    );

    $count = count($paths);

    for ($i = 0; $i < $count; $i++) {
        if (file_exists($paths[$i] . $classname . ".class.php")) {

            require_once $paths[$i] . $classname . ".class.php";
        }
    }
}

//register custom autoload function
spl_autoload_register('autoload');
