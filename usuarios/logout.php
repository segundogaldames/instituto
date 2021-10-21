<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../class/rutas.php');

session_start();
//print_r($_SESSION);

if (isset($_SESSION['autenticado'])) {

    #eliminamos las variables de session
    session_destroy();
    //$_SESSION['success'] = 'Su sesión se ha cerrado correctamente';
    header('Location: ' . BASE_URL);
}