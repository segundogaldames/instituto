<?php

$usuario = 'root';
$clave = 1234;

/* para windows la clave es vacia
$clave = ''; */

try {
    $mbd = new PDO('mysql:host=localhost;dbname=instituto', $usuario, $clave);
    /* foreach($mbd->query('SELECT * from FOO') as $fila) {
        print_r($fila);
    }
    $mbd = null; */
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
