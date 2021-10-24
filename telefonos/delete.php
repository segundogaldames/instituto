<?php

require('../class/rutas.php');
require('../class/conexion.php');
require('../class/config.php');

session_start();

if (isset($_SESSION['autenticado'])) {
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $id = (int) $_POST['telefono'];

        $res = $mbd->prepare("SELECT t.id, t.numero, t.fijo, t.telefonoable_id, t.telefonoable_type, f.nombre, u.id as usuario FROM funcionarios f INNER JOIN telefonos t ON t.telefonoable_id = f.id INNER JOIN usuarios u ON u.usuarioable_id = f.id WHERE t.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $telefono = $res->fetch();

        if ($telefono) {
            if($_SESSION['usuario_id'] == $telefono['usuario']){
                $res = $mbd->prepare("DELETE FROM telefonos WHERE id = ?");
                $res->bindParam(1, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El teléfono se ha eliminado correctamente';
                    header('Location: ' . FUNCIONARIOS . 'show.php?id=' . $telefono['telefonoable_id']);
                }
            }else {
                $_SESSION['danger'] = 'Operación no permitida';
                header('Location: ' . BASE_URL);
            }
        }
    }
}else{
    $_SESSION['danger'] = 'Operación no permitida';
    header('Location: ' . BASE_URL);
}