<?php
#muestran errores de codigo de php en tiempo de ejecucion
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)'){

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $region_id = (int) $_POST['region'];
            #consultar por la region que se quiere eliminar
            $res = $mbd->prepare("SELECT id FROM regiones WHERE id = ?");
            $res->bindParam(1, $region_id);
            $res->execute();
            $region = $res->fetch();

            if ($region) {
                # verificar que la region no tenga comunas asociadas
                $res = $mbd->prepare("SELECT id FROM comunas WHERE region_id = ?");
                $res->bindParam(1, $region_id);
                $res->execute();
                $comunas = $res->fetchall();

                if (!count($comunas)) {
                    #eliminar la region
                    $res = $mbd->prepare("DELETE FROM regiones WHERE id = ?");
                    $res->bindParam(1, $region_id);
                    $res->execute();

                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'La región se ha eliminado correctamente';
                        header('Location: ' . REGIONES);
                    }
                }else {
                    $_SESSION['danger'] = 'Esta región no puede eliminarse porque tiene comunas asociadas';
                    header('Location: ' . REGIONES . 'show.php?id=' . $region_id);
                }
            }else {
                $_SESSION['danger'] = 'Esta región no existe...';
                header('Location: ' . REGIONES . 'show.php?id=' . $region_id);
            }
        }
    }else{
        $_SESSION['danger'] = 'Operación no permitida';
        header('Location: ' . BASE_URL);
    }