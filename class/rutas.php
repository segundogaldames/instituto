<?php
#definicion de rutas en formato de constantes
define('BASE_URL','http://localhost:8080/instituto/');
define('REGIONES', BASE_URL . 'regiones/');
define('COMUNAS', BASE_URL . 'comunas/');
define('ROLES', BASE_URL . 'roles/');
define('FUNCIONARIOS', BASE_URL . 'funcionarios/');

define('PARAM', false);

#rutas de usuario
define('USUARIOS', BASE_URL . 'usuarios/');
define('ADD_USUARIO', USUARIOS . 'add.php?funcionario=' . PARAM);
define('EDIT_USUARIO', USUARIOS . 'edit.php?usuario=' . PARAM);

