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
define('LOGIN', USUARIOS . 'login.php');
define('LOGOUT', USUARIOS . 'logout.php');
define('EDIT_PASS', USUARIOS . 'editPassword.php?usuario=' . PARAM);

#rutas telefonos
define('TELEFONO', BASE_URL . 'telefonos/');
define('ADD_TELEFONO', TELEFONO . 'add.php?funcionario=' . PARAM);
define('SHOW_TELEFONO', TELEFONO . 'show.php?telefono=' . PARAM);
define('EDIT_TELEFONO', TELEFONO . 'edit.php?telefono=' . PARAM);
define('DEL_TELEFONO', TELEFONO . 'delete.php');

