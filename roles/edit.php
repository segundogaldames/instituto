<?php
    #muestran errores de codigo de php en tiempo de ejecucion
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    #valdamos el valor del id que se ha enviado desde el index
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        #consultar en la tabla roles si hay una rol con este id
        $res = $mbd->prepare("SELECT id, nombre FROM roles WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $rol = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $nombre = trim(strip_tags($_POST['nombre']));

            if (!$nombre) {
                $msg = 'Debe ingresar el nombre del rol';
            }else {
                #editar o modificar la rol segun el id enviado desde show
                $res = $mbd->prepare("UPDATE roles SET nombre = ? WHERE id = ?");
                $res->bindParam(1, $nombre);
                $res->bindParam(2, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El rol se ha modificado correctamente';
                    header('Location: ' . ROLES . 'show.php?id=' . $id);
                }
            }
        }

        //print_r($region);
    }

    // echo '<pre>';
    // print_r($regiones);exit;
    // echo '</pre>';
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador(a)'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- llamada a menu de navegacion -->
    <?php include('../partials/menu.php'); ?>

    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h3 class="text-primary">Editar Rol</h3>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($rol)): ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Rol</label>
                        <input type="text" name="nombre" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $rol['nombre']; ?>">
                        <div id="emailHelp" class="form-text">Ingresa el rol que deseas modificar.</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-primary">Editar</button>
                    <a href="<?php echo ROLES . 'show.php?id=' . $id; ?>" class="btn btn-link">Volver</a>
                </form>

            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
<?php else: ?>
    <?php
        $_SESSION['danger'] = 'Operación no permitida';
        header('Location: ' . BASE_URL);
    ?>
<?php endif; ?>